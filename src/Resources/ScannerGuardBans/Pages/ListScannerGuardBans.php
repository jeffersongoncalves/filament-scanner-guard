<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\PurgeExpiredBansAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;

class ListScannerGuardBans extends ListRecords
{
    protected static string $resource = ScannerGuardBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PurgeExpiredBansAction::make(),
        ];
    }
}
