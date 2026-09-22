<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources;

use Closure;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportBulkAction;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportHeaderAction;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBanResource extends Resource
{
    use HasPluginNavigationGroup;

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
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->formatStates(static::exportFormatStates()),
            ])
            ->actions([
                ViewAction::make(),
                ExtendBanAction::make(),
                UnbanAction::make(),
            ])
            ->bulkActions([
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('filament-scanner-guard::default.infolist.sections.ban_details'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('ip_hash')
                            ->label(__('filament-scanner-guard::default.table.ip_hash'))
                            ->copyable()
                            ->fontFamily('mono'),
                        TextEntry::make('reason')
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
                        TextEntry::make('matched_value')
                            ->label(__('filament-scanner-guard::default.table.matched_value'))
                            ->copyable()
                            ->columnSpanFull(),
                        TextEntry::make('hit_count')
                            ->label(__('filament-scanner-guard::default.table.hit_count'))
                            ->numeric(),
                        IconEntry::make('is_active')
                            ->label(__('filament-scanner-guard::default.table.is_active'))
                            ->boolean(),
                        TextEntry::make('banned_at')
                            ->label(__('filament-scanner-guard::default.table.banned_at'))
                            ->dateTime(),
                        TextEntry::make('expires_at')
                            ->label(__('filament-scanner-guard::default.table.expires_at'))
                            ->dateTime(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardBans::route('/'),
            'view' => ViewScannerGuardBan::route('/{record}'),
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
