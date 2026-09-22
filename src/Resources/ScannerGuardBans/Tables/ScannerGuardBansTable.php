<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables;

use Closure;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportBulkAction;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportHeaderAction;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip_hash')
                    ->label(__('filament-scanner-guard::default.table.ip_hash'))
                    ->fontFamily('mono')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('reason')
                    ->label(__('filament-scanner-guard::default.table.reason'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        ScannerGuardBan::REASON_ASN => __('filament-scanner-guard::default.reason.asn_blocklist'),
                        default => __('filament-scanner-guard::default.reason.scanner_path'),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        ScannerGuardBan::REASON_ASN => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('matched_value')
                    ->label(__('filament-scanner-guard::default.table.matched_value'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('hit_count')
                    ->label(__('filament-scanner-guard::default.table.hit_count'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('banned_at')
                    ->label(__('filament-scanner-guard::default.table.banned_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label(__('filament-scanner-guard::default.table.expires_at'))
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('filament-scanner-guard::default.table.is_active'))
                    ->boolean(),
            ])
            ->defaultSort('banned_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('filament-scanner-guard::default.filter.label'))
                    ->placeholder(__('filament-scanner-guard::default.filter.placeholder'))
                    ->trueLabel(__('filament-scanner-guard::default.filter.true_label'))
                    ->falseLabel(__('filament-scanner-guard::default.filter.false_label'))
                    ->queries(
                        true: fn (Builder $query): Builder => $query->where('expires_at', '>', now()),
                        false: fn (Builder $query): Builder => $query->where('expires_at', '<=', now()),
                        blank: fn (Builder $query): Builder => $query,
                    ),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->formatStates(static::exportFormatStates()),
            ])
            ->recordActions([
                UnbanAction::make(),
            ])
            ->toolbarActions([
                FilamentExportBulkAction::make('export')
                    ->formatStates(static::exportFormatStates()),
                UnbanBulkAction::make(),
            ]);
    }

    /**
     * Export the full matched value (the column is truncated in the table) and
     * neutralise spreadsheet formulas: it comes straight from attacker request paths.
     *
     * @return array<string, Closure>
     */
    public static function exportFormatStates(): array
    {
        return [
            'matched_value' => fn (ScannerGuardBan $record): string => preg_match('/^[=+\-@\t\r]/', $record->matched_value)
                ? "'".$record->matched_value
                : $record->matched_value,
            'is_active' => fn (ScannerGuardBan $record): string => $record->is_active
                ? __('filament-scanner-guard::default.filter.true_label')
                : __('filament-scanner-guard::default.filter.false_label'),
        ];
    }
}
