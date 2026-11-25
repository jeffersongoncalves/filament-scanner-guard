<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class BansBySourceChart extends ChartWidget
{
    protected ?string $heading = null;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return ScannerGuardPlugin::receivesForwardedBans();
    }

    public function getHeading(): ?string
    {
        return $this->heading ?? __('filament-scanner-guard::default.charts.bans_by_source');
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
