<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use Throwable;

class UnbanBulkAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'unban';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.unban.bulk_label'))
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (Collection $records): void {
                foreach ($records as $record) {
                    if (! $record instanceof ScannerGuardBan) {
                        continue;
                    }

                    // One failing ban (e.g. cache store down) must not leave the rest of the selection banned.
                    try {
                        Denylist::lift($record);
                    } catch (Throwable $e) {
                        report($e);
                    }
                }
            })
            ->deselectRecordsAfterCompletion()
            ->successNotificationTitle(__('filament-scanner-guard::default.unban.success'));
    }
}
