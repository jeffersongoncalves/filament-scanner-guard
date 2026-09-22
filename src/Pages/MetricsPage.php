<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Pages;

use Filament\Pages\Page;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\BansPerDayChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\TopMatchedValuesChart;

class MetricsPage extends Page
{
    use HasPluginNavigationGroup;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    // Filament v3 pages have no default view; header/footer widgets render around this empty page.
    protected static string $view = 'filament-scanner-guard::pages.metrics';

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

    protected function getFooterWidgets(): array
    {
        return [
            BansPerDayChart::class,
            BansByReasonChart::class,
            TopMatchedValuesChart::class,
        ];
    }
}
