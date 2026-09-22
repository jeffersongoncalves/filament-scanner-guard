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
        $scannerPath = ScannerGuardBan::query()
            ->where('reason', ScannerGuardBan::REASON_SCANNER_PATH)
            ->count();

        $asn = ScannerGuardBan::query()
            ->where('reason', ScannerGuardBan::REASON_ASN)
            ->count();

        return [
            'datasets' => [
                [
                    'data' => [$scannerPath, $asn],
                ],
            ],
            'labels' => [
                __('filament-scanner-guard::default.reason.scanner_path'),
                __('filament-scanner-guard::default.reason.asn_blocklist'),
            ],
        ];
    }
}
