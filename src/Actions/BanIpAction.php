<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
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
            ->icon(Heroicon::OutlinedNoSymbol)
            ->color('danger')
            ->schema([
                TextInput::make('ip')
                    ->label(__('filament-scanner-guard::default.ban_ip.form.ip'))
                    ->required()
                    ->ip(),
            ])
            ->action(function (array $data): void {
                app(ScannerGuard::class)->ban(static::canonicalIp((string) $data['ip']), self::REASON, 'panel', 0);
            })
            ->successNotificationTitle(__('filament-scanner-guard::default.ban_ip.success'));
    }

    /**
     * The core hashes the IP text as-is and the web server reports IPv6
     * compressed ("2001:db8::1"), so "2001:0db8::0001" must be compressed too
     * or the ban never matches. Input is already validated by ->ip().
     */
    public static function canonicalIp(string $ip): string
    {
        $packed = @inet_pton($ip);

        return $packed === false ? $ip : (string) inet_ntop($packed);
    }
}
