<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Exports;

use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBanExporter extends Exporter
{
    protected static ?string $model = ScannerGuardBan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('ip_hash')
                ->label(__('filament-scanner-guard::default.table.ip_hash')),
            ExportColumn::make('reason')
                ->label(__('filament-scanner-guard::default.table.reason'))
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    ScannerGuardBan::REASON_ASN => __('filament-scanner-guard::default.reason.asn_blocklist'),
                    default => __('filament-scanner-guard::default.reason.scanner_path'),
                }),
            ExportColumn::make('matched_value')
                ->label(__('filament-scanner-guard::default.table.matched_value'))
                ->preventFormulaInjection(),
            ExportColumn::make('hit_count')
                ->label(__('filament-scanner-guard::default.table.hit_count')),
            ExportColumn::make('banned_at')
                ->label(__('filament-scanner-guard::default.table.banned_at')),
            ExportColumn::make('expires_at')
                ->label(__('filament-scanner-guard::default.table.expires_at')),
            ExportColumn::make('is_active')
                ->label(__('filament-scanner-guard::default.table.is_active'))
                ->formatStateUsing(fn (bool $state): string => $state
                    ? __('filament-scanner-guard::default.filter.true_label')
                    : __('filament-scanner-guard::default.filter.false_label')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = __('filament-scanner-guard::default.export.completed', ['count' => number_format($export->successful_rows)]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.__('filament-scanner-guard::default.export.failed', ['count' => number_format($failedRowsCount)]);
        }

        return $body;
    }
}
