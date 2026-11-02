<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\BanIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Jobs\ExportDenylist;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBlocklistEntry;
use Livewire\Livewire;

it('manages the core blocklist model', function () {
    expect(ScannerGuardBlocklistResource::getModel())->toBe(ScannerGuardBlocklistEntry::class);
});

it('validates IP entries and accepts CIDR ranges', function (string $value, bool $valid) {
    expect(ScannerGuardBlocklistResource::isValidRange($value))->toBe($valid);
})->with([
    ['1.2.3.4', true],
    ['185.177.72.0/24', true],
    ['2001:db8::/32', true],
    ['1.2.3.4/33', false],
    ['not-an-ip', false],
]);

it('creates entries from the blocklist page and rejects invalid IPs', function () {
    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'ip', 'value' => 'nope'])
        ->assertHasActionErrors(['value']);

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'ip', 'value' => ' 9.9.9.0/24 ', 'note' => 'AS211590'])
        ->assertHasNoActionErrors();

    expect(ScannerGuardBlocklistEntry::where('value', '9.9.9.0/24')->exists())->toBeTrue()
        ->and(ScannerGuard::isIpBlocked('9.9.9.9'))->toBeTrue();
});

it('blocks User-Agent patterns added from the panel', function () {
    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'user_agent', 'value' => '*zgrab*'])
        ->assertHasNoActionErrors();

    expect(ScannerGuard::matchUserAgent('Mozilla/5.0 zgrab/0.x'))->toBe('*zgrab*');
});

it('rejects a duplicate entry of the same type', function () {
    ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => '1.2.3.4']);

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'ip', 'value' => '1.2.3.4'])
        ->assertHasActionErrors(['value']);
});

it('drops bulk-deleted entries from the core blocklist', function () {
    $entries = collect(['5.5.5.5', '6.6.6.6'])->map(fn (string $ip) => ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => $ip]));

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callTableBulkAction('delete', $entries);

    expect(ScannerGuard::ipBlocklist())->toBe([]);
});

it('queues the nginx export on a blocklist change when sync_to_nginx is on', function () {
    Queue::fake();
    config()->set('scanner-guard.sync_to_nginx', true);

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'ip', 'value' => '1.2.3.4'])
        ->assertHasNoActionErrors();

    Queue::assertPushed(ExportDenylist::class);
});

it('bans a raw IP from the bans list', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->callAction('banIp', ['ip' => '203.0.113.7'])
        ->assertHasNoActionErrors();

    $ban = ScannerGuardBan::query()->sole();

    expect($ban->reason)->toBe(BanIpAction::REASON)
        ->and($ban->ip_hash)->toBe(ScannerGuard::hashIp('203.0.113.7'))
        ->and(ScannerGuard::rawIpFor($ban->ip_hash))->toBe('203.0.113.7')
        ->and(ScannerGuard::isBanned('203.0.113.7'))->toBeTrue();
});

it('stores a manual IPv6 ban under the compressed form the server reports', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->callAction('banIp', ['ip' => '2001:0DB8:0000:0000:0000:0000:0000:0001'])
        ->assertHasNoActionErrors();

    expect(ScannerGuard::isBanned('2001:db8::1'))->toBeTrue()
        ->and(BanIpAction::canonicalIp('203.0.113.7'))->toBe('203.0.113.7');
});

it('lifts a ban at once, not when the cached ban expires', function () {
    ScannerGuard::ban('203.0.113.8', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);
    $ban = ScannerGuardBan::query()->sole();

    Livewire::test(ListScannerGuardBans::class)
        ->callTableAction('unban', $ban);

    expect(ScannerGuardBan::query()->count())->toBe(0)
        ->and(ScannerGuard::isBanned('203.0.113.8'))->toBeFalse();
});

it('unbans an expired row too', function () {
    $ban = createBan(['expires_at' => now()->subDay()]);

    Livewire::test(ListScannerGuardBans::class)
        ->callTableAction('unban', $ban);

    expect(ScannerGuardBan::find($ban->getKey()))->toBeNull();
});

it('still lifts every selected ban when queueing the export fails', function () {
    config()->set('scanner-guard.sync_to_nginx', true);
    Queue::shouldReceive('connection')->andThrow(new RuntimeException('queue down'));
    Log::spy();

    ScannerGuard::ban('203.0.113.9', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);
    ScannerGuard::ban('203.0.113.10', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);

    Livewire::test(ListScannerGuardBans::class)
        ->callTableBulkAction('unban', ScannerGuardBan::all());

    expect(ScannerGuardBan::query()->count())->toBe(0)
        ->and(ScannerGuard::isBanned('203.0.113.9'))->toBeFalse()
        ->and(ScannerGuard::isBanned('203.0.113.10'))->toBeFalse();

    Log::shouldHaveReceived('error')->with('scanner-guard: failed to queue nginx export', Mockery::any());
});

it('keeps lifting the other selected bans when one lift throws', function () {
    ScannerGuard::ban('203.0.113.11', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);
    ScannerGuard::ban('203.0.113.12', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);
    $failing = ScannerGuard::hashIp('203.0.113.11');

    ScannerGuardBan::deleting(function (ScannerGuardBan $ban) use ($failing): void {
        if ($ban->ip_hash === $failing) {
            throw new RuntimeException('cache down');
        }
    });

    Livewire::test(ListScannerGuardBans::class)
        ->callTableBulkAction('unban', ScannerGuardBan::query()->orderBy('id')->get());

    expect(ScannerGuardBan::query()->pluck('ip_hash')->all())->toBe([$failing])
        ->and(ScannerGuard::isBanned('203.0.113.12'))->toBeFalse();
});
