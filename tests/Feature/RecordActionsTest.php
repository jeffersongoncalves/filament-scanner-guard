<?php

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use Livewire\Livewire;

$pages = ['bans' => [ListScannerGuardBans::class], 'blocklist' => [ManageScannerGuardBlocklist::class]];

it('keeps record actions loose by default', function (string $page) {
    $table = Livewire::test($page)->instance()->getTable();

    expect($table->getActions())->not->toBeEmpty()
        ->and($table->getActions()[0])->not->toBeInstanceOf(ActionGroup::class)
        ->and($table->getActionsPosition())->not->toBe(ActionsPosition::BeforeColumns);
})->with($pages);

it('groups and positions record actions from the plugin', function (string $page) {
    ScannerGuardPlugin::get()
        ->groupRecordActions()
        ->recordActionsPosition(ActionsPosition::BeforeColumns);

    $table = Livewire::test($page)->instance()->getTable();

    expect($table->getActions())->toHaveCount(1)
        ->and($table->getActions()[0])->toBeInstanceOf(ActionGroup::class)
        ->and($table->getActionsPosition())->toBe(ActionsPosition::BeforeColumns);
})->with($pages);
