<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\DiagnoseIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\SetupAgentAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnosisResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentDiagnosis;

class ListScannerGuardAgents extends ListRecords
{
    protected static string $resource = ScannerGuardAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Core 2.8+ only: the action's form names the core's diagnosis model.
            ...(class_exists(ScannerGuardAgentDiagnosis::class) ? [
                DiagnoseIpAction::make()
                    ->after(fn () => $this->redirect(ScannerGuardAgentDiagnosisResource::getUrl())),
            ] : []),
            SetupAgentAction::make(),
        ];
    }
}
