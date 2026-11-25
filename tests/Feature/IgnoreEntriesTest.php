<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBlocklistEntry;
use Livewire\Livewire;

it('offers the never block type only when the core applies it', function () {
    expect(array_key_exists(ScannerGuardBlocklistResource::TYPE_IGNORE, ScannerGuardBlocklistResource::typeOptions()))
        ->toBe(ScannerGuardBlocklistResource::supportsIgnore());
});

it('refuses ranges the core would drop', function (string $entry, bool $tooBroad) {
    expect(ScannerGuardBlocklistResource::isTooBroad($entry))->toBe($tooBroad);
})->with([
    ['203.0.113.10', false],
    ['10.0.0.0/8', false],
    ['10.0.0.0/7', true],
    ['0.0.0.0/0', true],
    ['2001:db8::/16', false],
    ['::/0', true],
]);

it('adds never block entries from the panel', function () {
    if (! ScannerGuardBlocklistResource::supportsIgnore()) {
        $this->markTestSkipped('The installed core has no ignore list (2.3+).');
    }

    foreach (['nope', '0.0.0.0/0'] as $value) {
        Livewire::test(ManageScannerGuardBlocklist::class)
            ->callAction('create', ['type' => 'ignore', 'value' => $value])
            ->assertHasActionErrors(['value']);
    }

    Livewire::test(ManageScannerGuardBlocklist::class)
        ->callAction('create', ['type' => 'ignore', 'value' => '203.0.113.10'])
        ->assertHasNoActionErrors();

    expect(ScannerGuardBlocklistEntry::query()->sole()->type)->toBe(ScannerGuardBlocklistResource::TYPE_IGNORE)
        ->and(ScannerGuard::isIpIgnored('203.0.113.10'))->toBeTrue()
        ->and(ScannerGuard::isIpBlocked('203.0.113.10'))->toBeFalse();
});
