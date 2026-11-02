<?php

use Illuminate\Support\Facades\Queue;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\BanIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Models\ScannerGuardBlocklistEntry;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Jobs\ExportDenylist;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use Livewire\Livewire;

it('merges panel IP entries into the core blocklist and drops them on delete', function () {
    $entry = ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => '185.177.72.0/24', 'note' => 'curl scanner']);

    expect(config('scanner-guard.ip_blocklist'))->toContain('185.177.72.0/24')
        ->and(ScannerGuard::isIpBlocked('185.177.72.69'))->toBeTrue();

    $entry->delete();

    expect(ScannerGuard::isIpBlocked('185.177.72.69'))->toBeFalse();
});

it('merges panel User-Agent patterns into the core blocklist', function () {
    ScannerGuardBlocklistEntry::create(['type' => 'user_agent', 'value' => '*zgrab*']);

    expect(ScannerGuard::matchUserAgent('Mozilla/5.0 zgrab/0.x'))->toBe('*zgrab*');
});

it('keeps the config entries next to the panel ones', function () {
    $base = (new ReflectionProperty(Denylist::class, 'base'));
    $base->setValue(null, null);
    config()->set('scanner-guard.ip_blocklist', ['10.0.0.1']);

    ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => '10.0.0.2']);

    expect(config('scanner-guard.ip_blocklist'))->toBe(['10.0.0.1', '10.0.0.2']);

    $base->setValue(null, null);
});

it('queues the nginx export on a blocklist change when sync_to_nginx is on', function () {
    Queue::fake();
    config()->set('scanner-guard.sync_to_nginx', true);

    ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => '1.2.3.4']);

    Queue::assertPushed(ExportDenylist::class);
});

it('validates IP entries and accepts CIDR ranges', function (string $value, bool $valid) {
    expect(Denylist::isValidRange($value))->toBe($valid);
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

it('drops bulk-deleted entries from the core blocklist', function () {
    $entries = collect(['5.5.5.5', '6.6.6.6'])->map(fn (string $ip) => ScannerGuardBlocklistEntry::create(['type' => 'ip', 'value' => $ip]));

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callTableBulkAction('delete', $entries);

    expect(config('scanner-guard.ip_blocklist'))->toBe([]);
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

it('lifts a ban at once, not when the cached ban expires', function () {
    ScannerGuard::ban('203.0.113.8', ScannerGuardBan::REASON_SCANNER_PATH, '.env', 3);
    $ban = ScannerGuardBan::query()->sole();

    Livewire::test(ListScannerGuardBans::class)
        ->callTableAction('unban', $ban);

    expect(ScannerGuardBan::query()->count())->toBe(0)
        ->and(ScannerGuard::isBanned('203.0.113.8'))->toBeFalse();
});
