<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets;

use Filament\Widgets\ChartWidget;
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
        $counts = ScannerGuardBan::query()
            ->toBase()
            ->selectRaw('reason, count(*) as total')
            ->groupBy('reason')
            ->pluck('total', 'reason');

        return [
            'datasets' => [
                [
                    'data' => [
                        (int) ($counts[ScannerGuardBan::REASON_SCANNER_PATH] ?? 0),
                        (int) ($counts[ScannerGuardBan::REASON_ASN] ?? 0),
                    ],
                ],
            ],
            'labels' => [
                __('filament-scanner-guard::default.reason.scanner_path'),
                __('filament-scanner-guard::default.reason.asn_blocklist'),
            ],
        ];
    }
}
