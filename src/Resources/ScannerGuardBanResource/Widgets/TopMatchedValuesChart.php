<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class TopMatchedValuesChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('filament-scanner-guard::default.charts.top_matched_values');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = ScannerGuardBan::query()
            ->toBase()
            ->selectRaw('matched_value, SUM(hit_count) as total_hits')
            ->groupBy('matched_value')
            ->orderByDesc('total_hits')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('filament-scanner-guard::default.table.hit_count'),
                    'data' => $rows->pluck('total_hits')->map(fn ($value): int => (int) $value)->all(),
                ],
            ],
            'labels' => $rows->pluck('matched_value')->map(fn (string $value): string => mb_strimwidth($value, 0, 30, '…'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
