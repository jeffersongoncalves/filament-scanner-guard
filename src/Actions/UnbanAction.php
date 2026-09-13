<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Tables\Actions\Action;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class UnbanAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'unban';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.unban.label'))
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (ScannerGuardBan $record): void {
                $record->delete();
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.unban.success'));
    }
}
