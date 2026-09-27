<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Artisan;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class PurgeExpiredBansAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'purgeExpired';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.purge.label'))
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (): void {
                // Reconcile daily stats from the remaining rows first, so bans
                // recorded before ban-time counting still keep their day.
                Artisan::call('scanner-guard:aggregate-and-prune');

                ScannerGuardBan::query()
                    ->where('expires_at', '<=', now())
                    ->delete();
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.purge.success'));
    }
}
