<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanPageAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource;

class ViewScannerGuardBan extends ViewRecord
{
    protected static string $resource = ScannerGuardBanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExtendBanPageAction::make(),
            // Unban = delete the row; DeleteAction also redirects back to the list.
            DeleteAction::make('unban')
                ->label(__('filament-scanner-guard::default.unban.label'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->successNotificationTitle(__('filament-scanner-guard::default.unban.success')),
        ];
    }
}
