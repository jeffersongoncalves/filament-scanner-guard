<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class BansPerDayChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return $this->heading ?? __('filament-scanner-guard::default.charts.bans_per_day');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = 14;
        $labels = [];
        $values = [];
        $today = now()->startOfDay();

        $bans = ScannerGuardBan::query()
            ->where('banned_at', '>=', $today->copy()->subDays($days - 1))
            ->get(['banned_at'])
            ->groupBy(fn (ScannerGuardBan $ban): string => $ban->banned_at->format('Y-m-d'));

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $labels[] = $date->format('M d');
            $values[] = ($bans[$date->format('Y-m-d')] ?? collect())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => __('filament-scanner-guard::default.charts.bans_per_day'),
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
