<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class BansByReasonChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return $this->heading ?? __('filament-scanner-guard::default.charts.bans_by_reason');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        // Every stored reason, not a fixed list: forwarded agent bans carry free-form rule names.
        $counts = ScannerGuardBan::query()
            ->toBase()
            ->selectRaw('reason, count(*) as total')
            ->groupBy('reason')
            ->orderByDesc('total')
            ->orderBy('reason')
            ->pluck('total', 'reason');

        return [
            'datasets' => [
                [
                    'data' => $counts->map(fn (mixed $total): int => (int) $total)->values()->all(),
                ],
            ],
            'labels' => $counts->keys()->map(fn (mixed $reason): string => ScannerGuardBansTable::reasonLabel((string) $reason))->all(),
        ];
    }
}
