<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;

/**
 * Bans a raw IP through the core, like the middleware would: hashed row,
 * raw IP in cache for the nginx export, ban_duration, export on ban.
 */
class BanIpAction extends Action
{
    public const REASON = 'manual';

    public static function getDefaultName(): ?string
    {
        return 'banIp';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.ban_ip.label'))
            ->icon('heroicon-o-no-symbol')
            ->color('danger')
            ->form([
                TextInput::make('ip')
                    ->label(__('filament-scanner-guard::default.ban_ip.form.ip'))
                    ->required()
                    ->ip(),
            ])
            ->action(function (array $data): void {
                app(ScannerGuard::class)->ban((string) $data['ip'], self::REASON, 'panel', 0);
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.ban_ip.success'));
    }
}
