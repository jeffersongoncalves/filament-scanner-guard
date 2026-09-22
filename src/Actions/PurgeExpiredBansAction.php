<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
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
            ->icon(Heroicon::OutlinedTrash)
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (): void {
                ScannerGuardBan::query()
                    ->where('expires_at', '<=', now())
                    ->delete();
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.purge.success'));
    }
}
