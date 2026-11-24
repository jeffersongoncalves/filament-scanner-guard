<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables;

use Closure;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\BanIpAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportBulkAction;
use JeffersonGoncalves\FilamentExportAction\Actions\FilamentExportHeaderAction;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;

class ScannerGuardBansTable
{
    /** Filter value for locally banned rows (null source). */
    public const LOCAL_SOURCE = 'local';

    /** Satellite filter values carry this prefix, so no source name can collide with LOCAL_SOURCE. */
    public const SOURCE_PREFIX = 'source:';

    public static function configure(Table $table): Table
    {
        return ScannerGuardPlugin::get()->applyRecordActions($table, [
            ViewAction::make(),
            ExtendBanAction::make(),
            UnbanAction::make(),
        ])
            ->columns([
                TextColumn::make('ip_hash')
                    ->label(__('filament-scanner-guard::default.table.ip_hash'))
                    ->fontFamily('mono')
                    // The stored IP is encrypted: an IP is looked up by its hash, details on or off.
                    ->searchable(query: fn (Builder $query, string $search): Builder => filter_var($search, FILTER_VALIDATE_IP) === false
                        ? $query->where('ip_hash', 'like', '%'.strtolower($search).'%')
                        : $query->where('ip_hash', app(ScannerGuard::class)->hashIp(BanIpAction::canonicalIp($search))))
                    ->copyable(),
                TextColumn::make('ip')
                    ->label(__('filament-scanner-guard::default.ban_ip.form.ip'))
                    ->fontFamily('mono')
                    ->getStateUsing(fn (ScannerGuardBan $record): ?string => static::storedIp($record))
                    ->placeholder('—')
                    ->copyable()
                    ->toggleable()
                    ->visible(fn (): bool => ScannerGuardPlugin::storesDetails()),
                TextColumn::make('reason')
                    ->label(__('filament-scanner-guard::default.table.reason'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::reasonLabel($state))
                    ->color(fn (string $state): string => static::reasonColor($state)),
                TextColumn::make('matched_value')
                    ->label(__('filament-scanner-guard::default.table.matched_value'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('path')
                    ->label(__('filament-scanner-guard::default.table.path'))
                    ->placeholder('—')
                    ->searchable()
                    ->limit(40)
                    ->toggleable()
                    ->visible(fn (): bool => ScannerGuardPlugin::storesDetails()),
                TextColumn::make('hit_count')
                    ->label(__('filament-scanner-guard::default.table.hit_count'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('offense')
                    ->label(__('filament-scanner-guard::default.table.offense'))
                    ->badge()
                    ->color(fn (int $state): string => $state > 1 ? 'danger' : 'gray')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable()
                    ->visible(fn (): bool => ScannerGuardPlugin::escalationEnabled()),
                TextColumn::make('source')
                    ->label(__('filament-scanner-guard::default.table.source'))
                    ->badge()
                    ->color(fn (?string $state): string => $state === null ? 'gray' : 'info')
                    ->placeholder(__('filament-scanner-guard::default.table.local'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->visible(fn (): bool => ScannerGuardPlugin::receivesForwardedBans()),
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
                Filter::make('repeat_offenders')
                    ->label(__('filament-scanner-guard::default.filter.repeat_offenders'))
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('offense', '>', 1))
                    ->visible(fn (): bool => ScannerGuardPlugin::escalationEnabled()),
                SelectFilter::make('source')
                    ->label(__('filament-scanner-guard::default.table.source'))
                    ->options(fn (): array => static::sourceOptions())
                    ->query(fn (Builder $query, array $data): Builder => match (true) {
                        ($data['value'] ?? null) === static::LOCAL_SOURCE => $query->whereNull('source'),
                        str_starts_with((string) ($data['value'] ?? ''), static::SOURCE_PREFIX) => $query->where('source', substr($data['value'], strlen(static::SOURCE_PREFIX))),
                        default => $query,
                    })
                    ->visible(fn (): bool => ScannerGuardPlugin::receivesForwardedBans()),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->formatStates(static::exportFormatStates()),
            ])
            ->toolbarActions([
                FilamentExportBulkAction::make('export')
                    ->formatStates(static::exportFormatStates()),
                UnbanBulkAction::make(),
            ]);
    }

    public static function reasonLabel(string $reason): string
    {
        return match ($reason) {
            ScannerGuardBan::REASON_ASN => __('filament-scanner-guard::default.reason.asn_blocklist'),
            ScannerGuardBan::REASON_NOT_FOUND_FLOOD => __('filament-scanner-guard::default.reason.not_found_flood'),
            ScannerGuardBan::REASON_USER_AGENT => __('filament-scanner-guard::default.reason.user_agent'),
            BanIpAction::REASON => __('filament-scanner-guard::default.reason.manual'),
            default => __('filament-scanner-guard::default.reason.scanner_path'),
        };
    }

    public static function reasonColor(string $reason): string
    {
        return match ($reason) {
            ScannerGuardBan::REASON_ASN => 'danger',
            ScannerGuardBan::REASON_NOT_FOUND_FLOOD => 'info',
            ScannerGuardBan::REASON_USER_AGENT => 'primary',
            BanIpAction::REASON => 'gray',
            default => 'warning',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function sourceOptions(): array
    {
        /** @var list<string> $sources */
        $sources = ScannerGuardBan::query()
            ->whereNotNull('source')
            ->distinct()
            ->orderBy('source')
            ->pluck('source')
            ->all();

        $options = [static::LOCAL_SOURCE => __('filament-scanner-guard::default.table.local')];

        foreach ($sources as $source) {
            $options[static::SOURCE_PREFIX.$source] = $source;
        }

        return $options;
    }

    /**
     * Null when the row holds no IP, or one encrypted with a key this app no
     * longer has (APP_KEY rotated without APP_PREVIOUS_KEYS): that must not
     * take the whole page down.
     */
    public static function storedIp(ScannerGuardBan $record): ?string
    {
        try {
            return $record->getAttribute('ip');
        } catch (DecryptException) {
            return null;
        }
    }

    /**
     * Neutralises spreadsheet formulas in values that come from attacker requests.
     */
    protected static function exportSafe(string $value): string
    {
        return preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }

    /**
     * Export the full matched value and path (the columns are truncated in the
     * table), neutralised: they come straight from attacker request paths.
     *
     * @return array<string, Closure>
     */
    public static function exportFormatStates(): array
    {
        return [
            'matched_value' => fn (ScannerGuardBan $record): string => static::exportSafe($record->matched_value),
            'path' => fn (ScannerGuardBan $record): string => static::exportSafe((string) $record->path),
            'is_active' => fn (ScannerGuardBan $record): string => $record->is_active
                ? __('filament-scanner-guard::default.filter.true_label')
                : __('filament-scanner-guard::default.filter.false_label'),
        ];
    }
}
