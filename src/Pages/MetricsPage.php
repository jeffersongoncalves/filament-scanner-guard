<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansPerDayChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\TopMatchedValuesChart;

class MetricsPage extends Page
{
    use HasPluginNavigationGroup;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function getSlug(?Panel $panel = null): string
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
