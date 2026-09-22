<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages;

use Filament\Resources\Pages\ViewRecord;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;

class ViewScannerGuardBan extends ViewRecord
{
    protected static string $resource = ScannerGuardBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExtendBanAction::make(),
            // The record is deleted on unban, so leave the view page.
            UnbanAction::make()
                ->successRedirectUrl(ScannerGuardBanResource::getUrl('index')),
        ];
    }
}
