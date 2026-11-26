<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\ScannerGuardAgentRuleResource;

class ManageScannerGuardAgentRules extends ManageRecords
{
    protected static string $resource = ScannerGuardAgentRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
