<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;

class ScannerGuardPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-scanner-guard';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ScannerGuardBanResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
