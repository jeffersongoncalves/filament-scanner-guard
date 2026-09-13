<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $total = ScannerGuardBan::query()->count();
        $active = ScannerGuardBan::active()->count();
        $expired = $total - $active;
        $hits = (int) ScannerGuardBan::query()->sum('hit_count');

        return [
            Stat::make(__('filament-scanner-guard::default.stats.total_bans'), $total),
            Stat::make(__('filament-scanner-guard::default.stats.active_bans'), $active),
            Stat::make(__('filament-scanner-guard::default.stats.expired_bans'), $expired),
            Stat::make(__('filament-scanner-guard::default.stats.total_hits'), $hits),
        ];
    }
}
