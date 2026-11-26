<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\PurgeExpiredBansAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;
use Livewire\Livewire;

function skipWithoutDetails(): void
{
    if (! Schema::hasColumn('scanner_guard_bans', 'ip')) {
        test()->markTestSkipped('The installed core has no add_details migration (2.2+).');
    }
}

it('finds the bans of an ip by its hash, without stored details', function () {
    $hash = app(ScannerGuard::class)->hashIp('2001:db8::1');
    $ban = createBan(['ip_hash' => $hash]);
    $other = createBan(['ip_hash' => str_repeat('0', 64)]);

    foreach (['2001:0db8::0001', substr($hash, 0, 20), strtoupper($hash)] as $search) {
        Livewire::test(ListScannerGuardBans::class)
            ->searchTable($search)
            ->assertCanSeeTableRecords([$ban])
            ->assertCanNotSeeTableRecords([$other]);
    }
});

it('finds a forwarded ban by the expanded ipv6 text its satellite sent', function () {
    app(ScannerGuard::class)->recordForwardedBan('2001:0db8:0000:0000:0000:0000:0000:0001', ScannerGuardBan::REASON_SCANNER_PATH, 'wp-admin*', 3, now(), now()->addDay());
    $ban = ScannerGuardBan::query()->sole();
    $other = createBan();

    Livewire::test(ListScannerGuardBans::class)
        ->searchTable('2001:0db8:0000:0000:0000:0000:0000:0001')
        ->assertCanSeeTableRecords([$ban])
        ->assertCanNotSeeTableRecords([$other]);
});

it('hides the ip and path unless the app stores details', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnVisible('ip_hash')
        ->assertTableColumnHidden('ip')
        ->assertTableColumnHidden('path');
});

it('shows the ip instead of its hash and still finds bans by ip or hash', function () {
    skipWithoutDetails();
    config()->set('scanner-guard.store_details', true);

    $hash = app(ScannerGuard::class)->hashIp('203.0.113.5');
    $ban = createBan(['ip_hash' => $hash, 'ip' => '203.0.113.5']);
    $old = createBan(['ip_hash' => str_repeat('b', 64)]);

    foreach (['203.0.113.5', substr($hash, 0, 20)] as $search) {
        Livewire::test(ListScannerGuardBans::class)
            ->assertTableColumnHidden('ip_hash')
            ->assertTableColumnVisible('ip')
            ->searchTable($search)
            ->assertCanSeeTableRecords([$ban])
            ->assertCanNotSeeTableRecords([$old]);
    }

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertSee('203.0.113.5')
        ->assertDontSee($hash);

    // A ban stored before details were on has no ip: its view keeps the hash.
    Livewire::test(ViewScannerGuardBan::class, ['record' => $old->getKey()])
        ->assertSee(str_repeat('b', 64));
});

it('hides the ip and path until the add_details migration ran', function () {
    config()->set('scanner-guard.store_details', true);

    if (Schema::hasColumn('scanner_guard_bans', 'ip')) {
        Schema::table('scanner_guard_bans', fn (Blueprint $table) => $table->dropColumn(['ip', 'path']));
    }

    createBan(['ip_hash' => str_repeat('a', 64)]);

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnHidden('ip')
        ->assertTableColumnHidden('path')
        ->searchTable('wp-admin')
        ->assertOk();
});

it('shows the ip and path of a ban and searches the path', function () {
    skipWithoutDetails();
    config()->set('scanner-guard.store_details', true);

    $ban = createBan(['ip' => '203.0.113.5', 'path' => 'wp-admin/<b>install</b>.php']);
    $other = createBan();

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnStateSet('ip', '203.0.113.5', $ban)
        ->assertTableColumnStateSet('path', 'wp-admin/<b>install</b>.php', $ban)
        ->assertTableColumnStateSet('ip', null, $other)
        ->assertDontSeeHtml('<b>install</b>')
        ->searchTable('install')
        ->assertCanSeeTableRecords([$ban])
        ->assertCanNotSeeTableRecords([$other]);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertSee(['203.0.113.5', 'wp-admin/<b>install</b>.php'])
        ->assertDontSeeHtml('<b>install</b>');
});

it('still renders when a stored ip can no longer be decrypted', function () {
    skipWithoutDetails();
    config()->set('scanner-guard.store_details', true);

    $ban = createBan(['ip' => '203.0.113.5']);
    DB::table('scanner_guard_bans')->update(['ip' => 'encrypted-with-a-lost-key']);

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnStateSet('ip', null, $ban->fresh());

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertOk()
        ->callAction('extend', ['duration' => 3600])
        ->assertHasNoActionErrors();
});

/**
 * Stores the evidence of $ban as the core does (encrypted json), adding the
 * column when the installed core has no add_evidence migration yet.
 */
function storeEvidence(ScannerGuardBan $ban, mixed $lines): void
{
    if (! Schema::hasColumn('scanner_guard_bans', 'evidence')) {
        Schema::table('scanner_guard_bans', fn (Blueprint $table) => $table->text('evidence')->nullable());
    }

    DB::table('scanner_guard_bans')->where('id', $ban->getKey())->update([
        'evidence' => is_string($lines) ? $lines : Crypt::encryptString(json_encode($lines)),
    ]);
}

it('shows the log lines an agent banned on, escaped', function () {
    skipWithoutDetails();
    config()->set('scanner-guard.store_details', true);

    $ban = createBan(['ip' => '203.0.113.5']);
    storeEvidence($ban, ['<ip> - "GET /.env.staging HTTP/2.0" 403 rule=dotfile', '<ip> - "GET /<b>x</b> HTTP/2.0" 403 rule=xss']);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertSee(__('filament-scanner-guard::default.table.evidence'))
        ->assertSee(['<ip> - "GET /.env.staging HTTP/2.0" 403 rule=dotfile', '<ip> - "GET /<b>x</b> HTTP/2.0" 403 rule=xss'])
        ->assertDontSeeHtml('<b>x</b>');
});

it('hides the log lines when there are none to show', function (bool $details, mixed $lines) {
    skipWithoutDetails();
    config()->set('scanner-guard.store_details', $details);

    $ban = createBan();
    if ($lines !== null) {
        storeEvidence($ban, $lines);
    }

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertOk()
        ->assertDontSee(__('filament-scanner-guard::default.table.evidence'));
})->with([
    'details off' => [false, ['GET /.env']],
    'no column (older core)' => [true, null],
    'ban without evidence' => [true, []],
    'lost key' => [true, 'encrypted-with-a-lost-key'],
]);

it('exports the full path and neutralises spreadsheet formulas', function () {
    skipWithoutDetails();

    $format = ScannerGuardBansTable::exportFormatStates()['path'];
    $long = str_repeat('a', 60).'.php';

    expect($format(createBan(['path' => $long])))->toBe($long)
        ->and($format(createBan(['path' => '=1+1'])))->toBe("'=1+1")
        ->and($format(createBan()))->toBe('');
});

it('warns that deleting bans deletes their stored details', function () {
    skipWithoutDetails();

    $page = Livewire::test(ViewScannerGuardBan::class, ['record' => createBan()->getKey()])->instance();
    $descriptions = fn (): array => array_map(strval(...), [
        PurgeExpiredBansAction::make()->getModalDescription(),
        UnbanAction::make()->getModalDescription(),
        UnbanBulkAction::make()->getModalDescription(),
        $page->getAction('unban')->getModalDescription(),
    ]);

    // Details off: Filament's default confirmation text.
    expect(array_unique($descriptions()))->toBe([__('filament-actions::modal.confirmation')]);

    config()->set('scanner-guard.store_details', true);

    expect(array_unique($descriptions()))->toBe([__('filament-scanner-guard::default.delete_warning')]);
});
