<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource\Pages\ListScannerGuardAgents;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;

/**
 * Read-only: rows are written by the agents' heartbeats (core 2.1+). Delete
 * forgets a decommissioned agent; a live one comes back on its next heartbeat.
 */
class ScannerGuardAgentResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardAgent::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $recordTitleAttribute = 'source';

    public static function canAccess(): bool
    {
        return ScannerGuardPlugin::receivesHeartbeats() && parent::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ScannerGuardPlugin::get()->applyRecordActions($table, [
            DeleteAction::make(),
        ])
            ->columns([
                TextColumn::make('source')
                    ->label(__('filament-scanner-guard::default.agents.source'))
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament-scanner-guard::default.agents.status'))
                    ->state(fn (ScannerGuardAgent $record): bool => $record->isOnline())
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => __('filament-scanner-guard::default.agents.'.($state ? 'online' : 'offline')))
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('version')
                    ->label(__('filament-scanner-guard::default.agents.version'))
                    ->fontFamily('mono')
                    ->placeholder('—'),
                // Agent 2.9.0+ with the add_host migration of the core; empty before.
                TextColumn::make('os')
                    ->label(__('filament-scanner-guard::default.agents.os'))
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('addresses')
                    ->label(__('filament-scanner-guard::default.agents.addresses'))
                    ->fontFamily('mono')
                    ->listWithLineBreaks()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('rules')
                    ->label(__('filament-scanner-guard::default.agents.rules'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                TextColumn::make('active_bans')
                    ->label(__('filament-scanner-guard::default.agents.active_bans'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('started_at')
                    ->label(__('filament-scanner-guard::default.agents.started_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('last_seen_at')
                    ->label(__('filament-scanner-guard::default.agents.last_seen_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->defaultSort('last_seen_at', 'desc')
            // ponytail: heartbeats land every few seconds; 30s keeps the status fresh without hammering the DB.
            ->poll('30s')
            ->filters([
                TernaryFilter::make('status')
                    ->label(__('filament-scanner-guard::default.agents.status'))
                    ->trueLabel(__('filament-scanner-guard::default.agents.online'))
                    ->falseLabel(__('filament-scanner-guard::default.agents.offline'))
                    ->queries(
                        true: fn (Builder $query): Builder => $query->scopes('online'),
                        false: fn (Builder $query): Builder => $query->scopes('offline'),
                    ),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardAgents::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.agents.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.agents.plural_label');
    }
}
