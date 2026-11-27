<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnosisResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\DiagnoseIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnosisResource;

class ListScannerGuardAgentDiagnoses extends ListRecords
{
    protected static string $resource = ScannerGuardAgentDiagnosisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DiagnoseIpAction::make(),
        ];
    }
}
