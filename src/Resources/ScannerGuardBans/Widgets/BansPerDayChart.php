<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;

class BansPerDayChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return $this->heading ?? __('filament-scanner-guard::default.charts.bans_per_day', [
            'days' => ScannerGuardPlugin::get()->getChartDays(),
        ]);
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Daily stats are counted at ban time, so purging or unbanning rows
        // doesn't erase past days from the chart.
        $stats = ScannerGuard::dailyStats(ScannerGuardPlugin::get()->getChartDays());

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $stats->pluck('bans_count')->all(),
                ],
            ],
            'labels' => $stats->map(fn (array $day): string => Carbon::parse($day['date'])->translatedFormat('M d'))->all(),
        ];
    }
}
