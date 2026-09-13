<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBanResource extends Resource
{
    protected static ?string $model = ScannerGuardBan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $recordTitleAttribute = 'ip_hash';

    public static function table(Table $table): Table
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
            ->actions([
                UnbanAction::make(),
            ])
            ->bulkActions([
                UnbanBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardBans::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.resource.plural_label');
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
