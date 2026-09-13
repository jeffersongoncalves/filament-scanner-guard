<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;

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
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->requiresConfirmation()
            ->action(function (Collection $records): void {
                $records->each->delete();
            })
            ->deselectRecordsAfterCompletion()
            ->successNotificationTitle(__('filament-scanner-guard::default.unban.success'));
    }
}
