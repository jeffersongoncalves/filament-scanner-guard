<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

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
                    if ($record instanceof ScannerGuardBan) {
                        Denylist::lift($record);
                    }
                }
            })
            ->deselectRecordsAfterCompletion()
            ->successNotificationTitle(__('filament-scanner-guard::default.unban.success'));
    }
}
