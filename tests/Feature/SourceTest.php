<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use Livewire\Livewire;

it('hides the source column and filter unless the app receives forwarded bans', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnHidden('source')
        ->assertTableFilterHidden('source');
});

it('hides the source column on a central app until the add_source migration ran', function () {
    config()->set('scanner-guard.http.server.enabled', true);
    // Core 1.7 has no add_source migration: nothing to drop there.
    if (Schema::hasColumn('scanner_guard_bans', 'source')) {
        Schema::table('scanner_guard_bans', function (Blueprint $table): void {
            $table->dropIndex(['source']);
            $table->dropColumn('source');
        });
    }

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnHidden('source')
        ->assertTableFilterHidden('source');
});

it('shows the source and filters by satellite or local bans on the central app', function () {
    if (! Schema::hasColumn('scanner_guard_bans', 'source')) {
        $this->markTestSkipped('The installed core has no add_source migration.');
    }

    config()->set('scanner-guard.http.server.enabled', true);

    $local = createBan();
    $stats = createBan(['source' => 'stats']);
    // A satellite literally named like the local token must stay selectable.
    $shop = createBan(['source' => 'local']);

    expect(ScannerGuardBansTable::sourceOptions())->toBe([
        ScannerGuardBansTable::LOCAL_SOURCE => __('filament-scanner-guard::default.table.local'),
        'source:local' => 'local',
        'source:stats' => 'stats',
    ]);

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnVisible('source')
        ->filterTable('source', 'source:stats')
        ->assertCanSeeTableRecords([$stats])
        ->assertCanNotSeeTableRecords([$local, $shop])
        ->filterTable('source', 'source:local')
        ->assertCanSeeTableRecords([$shop])
        ->assertCanNotSeeTableRecords([$local, $stats])
        ->filterTable('source', ScannerGuardBansTable::LOCAL_SOURCE)
        ->assertCanSeeTableRecords([$local])
        ->assertCanNotSeeTableRecords([$stats, $shop]);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $stats->getKey()])
        ->assertSee('stats');
});
