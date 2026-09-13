<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Pages;

use Filament\Pages\Page;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\StatsOverview;

class MetricsPage extends Page
{
    use HasPluginNavigationGroup;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function getSlug(): string
    {
        return 'scanner-guard-metrics';
    }

    public function getTitle(): string
    {
        return __('filament-scanner-guard::default.metrics.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-scanner-guard::default.metrics.title');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
