<?php

use Filament\Actions\ActionGroup;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklist\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use Livewire\Livewire;

$pages = ['bans' => [ListScannerGuardBans::class], 'blocklist' => [ManageScannerGuardBlocklist::class]];

it('keeps record actions loose by default', function (string $page) {
    $table = Livewire::test($page)->instance()->getTable();

    expect($table->getRecordActions())->not->toBeEmpty()
        ->and($table->getRecordActions()[0])->not->toBeInstanceOf(ActionGroup::class)
        ->and($table->getRecordActionsPosition())->not->toBe(RecordActionsPosition::BeforeColumns);
})->with($pages);

it('keeps the global position when the position closure returns null', function (string $page) {
    Table::configureUsing(fn (Table $table) => $table->recordActionsPosition(RecordActionsPosition::BeforeColumns));
    ScannerGuardPlugin::get()->recordActionsPosition(fn () => null);

    expect(Livewire::test($page)->instance()->getTable()->getRecordActionsPosition())
        ->toBe(RecordActionsPosition::BeforeColumns);
})->with($pages);

it('groups and positions record actions from the plugin', function (string $page) {
    ScannerGuardPlugin::get()
        ->groupRecordActions()
        ->recordActionsPosition(RecordActionsPosition::BeforeColumns);

    $table = Livewire::test($page)->instance()->getTable();

    expect($table->getRecordActions())->toHaveCount(1)
        ->and($table->getRecordActions()[0])->toBeInstanceOf(ActionGroup::class)
        ->and($table->getRecordActionsPosition())->toBe(RecordActionsPosition::BeforeColumns);
})->with($pages);
