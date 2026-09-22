<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets;

use Filament\Widgets\ChartWidget;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class BansByReasonChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    public function getHeading(): ?string
    {
        return __('filament-scanner-guard::default.charts.bans_by_reason');
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
