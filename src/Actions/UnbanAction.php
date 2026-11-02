<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Tables\Actions\Action;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;

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
                static::lift($record);
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.unban.success'));
    }

    /**
     * Deletes this row (active or expired), then lets the core lift the IP:
     * its other active rows, the cached ban and the nginx export.
     */
    public static function lift(ScannerGuardBan $record): void
    {
        $record->delete();

        app(ScannerGuard::class)->unban($record->ip_hash);
    }
}
