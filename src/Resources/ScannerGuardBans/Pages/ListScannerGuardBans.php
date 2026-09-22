<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages;

use Filament\Resources\Pages\ListRecords;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansPerDayChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\TopMatchedValuesChart;

class ListScannerGuardBans extends ListRecords
{
    protected static string $resource = ScannerGuardBanResource::class;

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

