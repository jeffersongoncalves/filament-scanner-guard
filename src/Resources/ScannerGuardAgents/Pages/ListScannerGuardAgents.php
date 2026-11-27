<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgents\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\DiagnoseIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\SetupAgentAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnoses\ScannerGuardAgentDiagnosisResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgents\ScannerGuardAgentResource;

class ListScannerGuardAgents extends ListRecords
{
    protected static string $resource = ScannerGuardAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DiagnoseIpAction::make()
                ->after(fn () => $this->redirect(ScannerGuardAgentDiagnosisResource::getUrl())),
            SetupAgentAction::make(),
        ];
    }
}
