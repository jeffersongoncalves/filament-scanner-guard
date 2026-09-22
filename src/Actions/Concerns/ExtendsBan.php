<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions\Concerns;

use Filament\Forms\Components\Select;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

/**
 * Filament v3 keeps table and page actions in separate class trees, so the
 * extend behaviour lives here and is shared by ExtendBanAction (table row)
 * and ExtendBanPageAction (view page header).
 */
trait ExtendsBan
{
    public static function getDefaultName(): ?string
    {
        return 'extend';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.extend.label'))
            ->icon('heroicon-o-clock')
            ->color('warning')
            ->visible(fn (ScannerGuardBan $record): bool => $record->is_active)
            ->form([
                Select::make('duration')
                    ->label(__('filament-scanner-guard::default.extend.form.duration'))
                    ->options([
                        3600 => __('filament-scanner-guard::default.extend.options.hour'),
                        86400 => __('filament-scanner-guard::default.extend.options.day'),
                        604800 => __('filament-scanner-guard::default.extend.options.week'),
                        2592000 => __('filament-scanner-guard::default.extend.options.month'),
                    ])
                    ->default(86400)
                    ->required()
                    ->native(false),
            ])
            ->action(function (array $data, ScannerGuardBan $record): void {
                $base = $record->expires_at->isFuture() ? $record->expires_at : now();

                $record->update([
                    'expires_at' => $base->copy()->addSeconds((int) $data['duration']),
                ]);
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.extend.success'));
    }
}
