<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource;

class ManageScannerGuardBlocklist extends ManageRecords
{
    protected static string $resource = ScannerGuardBlocklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
