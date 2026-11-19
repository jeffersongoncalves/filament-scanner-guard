<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource as ScannerGuardBansTable;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ViewScannerGuardBan;
use Livewire\Livewire;

it('hides the source column and filter unless the app receives forwarded bans', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnHidden('source')
        ->assertTableFilterHidden('source');
});

it('shows the source and filters by satellite or local bans on the central app', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    $local = createBan();
    $stats = createBan(['source' => 'stats']);
    $shop = createBan(['source' => 'shop']);

    expect(ScannerGuardBansTable::sourceOptions())
        ->toBe([ScannerGuardBansTable::LOCAL_SOURCE => __('filament-scanner-guard::default.table.local'), 'shop' => 'shop', 'stats' => 'stats']);

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnVisible('source')
        ->filterTable('source', 'stats')
        ->assertCanSeeTableRecords([$stats])
        ->assertCanNotSeeTableRecords([$local, $shop])
        ->filterTable('source', ScannerGuardBansTable::LOCAL_SOURCE)
        ->assertCanSeeTableRecords([$local])
        ->assertCanNotSeeTableRecords([$stats, $shop]);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $stats->getKey()])
        ->assertSee('stats');
});
