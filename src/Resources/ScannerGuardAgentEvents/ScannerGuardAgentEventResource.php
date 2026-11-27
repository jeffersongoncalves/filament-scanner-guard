<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEvents;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Encryption\DecryptException;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEvents\Pages\ListScannerGuardAgentEvents;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentEvent;

/**
 * Read-only: rows are what the agents' report rules saw (core 2.6+), e.g.
 * every accepted SSH login.
 */
class ScannerGuardAgentEventResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardAgentEvent::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    public static function canAccess(): bool
    {
        return ScannerGuardPlugin::receivesAgentEvents() && parent::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('occurred_at')
                    ->label(__('filament-scanner-guard::default.agent_events.occurred_at'))
                    ->dateTime(),
                TextEntry::make('source')
                    ->label(__('filament-scanner-guard::default.agent_events.source')),
                TextEntry::make('rule')
                    ->label(__('filament-scanner-guard::default.agent_events.rule'))
                    ->badge(),
                TextEntry::make('ip')
                    ->label(__('filament-scanner-guard::default.agent_events.ip'))
                    ->getStateUsing(fn (ScannerGuardAgentEvent $record): ?string => static::decrypted($record, 'ip'))
                    ->placeholder('—'),
                TextEntry::make('pattern')
                    ->label(__('filament-scanner-guard::default.agent_events.pattern'))
                    ->fontFamily('mono')
                    ->placeholder('—'),
                KeyValueEntry::make('fields')
                    ->label(__('filament-scanner-guard::default.agent_events.fields')),
                // Attacker-controlled: TextEntry escapes it.
                TextEntry::make('line')
                    ->label(__('filament-scanner-guard::default.agent_events.line'))
                    ->getStateUsing(fn (ScannerGuardAgentEvent $record): ?string => static::decrypted($record, 'line'))
                    ->fontFamily('mono')
                    ->placeholder('—'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return ScannerGuardPlugin::get()->applyRecordActions($table, [
            ViewAction::make(),
            DeleteAction::make(),
        ])
            ->columns([
                TextColumn::make('occurred_at')
                    ->label(__('filament-scanner-guard::default.agent_events.occurred_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('source')
                    ->label(__('filament-scanner-guard::default.agent_events.source'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rule')
                    ->label(__('filament-scanner-guard::default.agent_events.rule'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('fields')
                    ->label(__('filament-scanner-guard::default.agent_events.fields'))
                    ->state(fn (ScannerGuardAgentEvent $record): string => static::compactFields($record->fields))
                    ->fontFamily('mono')
                    ->limit(60)
                    ->placeholder('—'),
                TextColumn::make('ip')
                    ->label(__('filament-scanner-guard::default.agent_events.ip'))
                    ->state(fn (ScannerGuardAgentEvent $record): ?string => static::decrypted($record, 'ip'))
                    ->placeholder('—'),
                TextColumn::make('first_seen')
                    ->label(__('filament-scanner-guard::default.agent_events.first_seen'))
                    ->state(fn (ScannerGuardAgentEvent $record): ?string => $record->first_seen ? __('filament-scanner-guard::default.agent_events.new') : null)
                    ->badge()
                    ->color('warning'),
                TextColumn::make('ignored')
                    ->label(__('filament-scanner-guard::default.agent_events.ignored'))
                    ->state(fn (ScannerGuardAgentEvent $record): ?string => $record->ignored ? __('filament-scanner-guard::default.agent_events.ignored') : null)
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->filters([
                SelectFilter::make('source')
                    ->label(__('filament-scanner-guard::default.agent_events.source'))
                    ->options(fn (): array => static::distinct('source')),
                SelectFilter::make('rule')
                    ->label(__('filament-scanner-guard::default.agent_events.rule'))
                    ->options(fn (): array => static::distinct('rule')),
                TernaryFilter::make('first_seen')
                    ->label(__('filament-scanner-guard::default.agent_events.first_seen')),
                TernaryFilter::make('ignored')
                    ->label(__('filament-scanner-guard::default.agent_events.ignored')),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * `user=deploy key=SHA256:…`: the fields that tell logins apart.
     *
     * @param  array<string, string>|null  $fields
     */
    public static function compactFields(?array $fields): string
    {
        return collect($fields ?? [])
            ->only(['user', 'method', 'key'])
            ->map(fn (mixed $value, string $key): string => "{$key}={$value}")
            ->implode(' ');
    }

    /**
     * Null when not stored, or encrypted with a key this app no longer has
     * (APP_KEY rotated): that must not take the whole page down.
     */
    public static function decrypted(ScannerGuardAgentEvent $record, string $attribute): ?string
    {
        try {
            return $record->getAttribute($attribute);
        } catch (DecryptException) {
            return null;
        }
    }

    /**
     * @return array<string, string>
     */
    protected static function distinct(string $column): array
    {
        return ScannerGuardAgentEvent::query()->distinct()->orderBy($column)->pluck($column, $column)->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardAgentEvents::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.agent_events.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.agent_events.plural_label');
    }
}
