<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRules\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRules\ScannerGuardAgentNginxRuleResource;

class ManageScannerGuardAgentNginxRules extends ManageRecords
{
    protected static string $resource = ScannerGuardAgentNginxRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => ScannerGuardAgentNginxRuleResource::nameOptions(null) !== []),
        ];
    }
}
