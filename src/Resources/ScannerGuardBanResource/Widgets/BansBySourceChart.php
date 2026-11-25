<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class BansBySourceChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return ScannerGuardPlugin::receivesForwardedBans();
    }

    public function getHeading(): ?string
    {
        return __('filament-scanner-guard::default.charts.bans_by_source');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        // One slice per satellite (server / service) that forwarded bans, plus the local ones (null source).
        $rows = ScannerGuardBan::query()
            ->toBase()
            ->selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->orderByDesc('total')
            ->orderBy('source')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $rows->map(fn (object $row): int => (int) $row->total)->all(),
                ],
            ],
            'labels' => $rows->map(fn (object $row): string => $row->source ?? __('filament-scanner-guard::default.table.local'))->all(),
        ];
    }
}
