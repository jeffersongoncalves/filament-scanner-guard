<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRuleResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRuleResource;

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
