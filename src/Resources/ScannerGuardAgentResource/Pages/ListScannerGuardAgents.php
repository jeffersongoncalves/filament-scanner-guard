<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\SetupAgentAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource;

class ListScannerGuardAgents extends ListRecords
{
    protected static string $resource = ScannerGuardAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SetupAgentAction::make(),
        ];
    }
}
