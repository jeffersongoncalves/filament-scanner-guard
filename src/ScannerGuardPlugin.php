<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Filament\Contracts\Plugin;
use Filament\Panel;
use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource;

class ScannerGuardPlugin implements Plugin
{
    protected ?string $navigationGroup = null;

    public function getId(): string
    {
        return 'filament-scanner-guard';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ScannerGuardBanResource::class,
            ])
            ->pages([
                MetricsPage::class,
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

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup ?? __('filament-scanner-guard::default.navigation.group');
    }
}
