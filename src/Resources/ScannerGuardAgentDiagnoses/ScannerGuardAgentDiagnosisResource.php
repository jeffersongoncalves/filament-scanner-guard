<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnoses;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\HtmlString;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnoses\Pages\ListScannerGuardAgentDiagnoses;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentDiagnosis;

/**
 * Diagnoses of an IP asked of the agents (core 2.8+): what blocks it on each
 * server, if anything. Rows are created by DiagnoseIpAction and answered by
 * the agents.
 */
class ScannerGuardAgentDiagnosisResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardAgentDiagnosis::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    public static function canAccess(): bool
    {
        return ScannerGuardPlugin::receivesAgentDiagnoses() && parent::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('source')
                    ->label(__('filament-scanner-guard::default.diagnoses.source')),
                TextEntry::make('ip')
                    ->label(__('filament-scanner-guard::default.diagnoses.ip'))
                    ->getStateUsing(fn (ScannerGuardAgentDiagnosis $record): ?string => static::decrypted($record, 'ip'))
                    ->placeholder('—'),
                // Quotes log lines an attacker may control: escaped before going in the <pre>.
                TextEntry::make('report')
                    ->label(__('filament-scanner-guard::default.diagnoses.report'))
                    ->getStateUsing(fn (ScannerGuardAgentDiagnosis $record): ?HtmlString => ($report = static::decrypted($record, 'report')) === null
                        ? null
                        : new HtmlString('<pre class="text-xs whitespace-pre-wrap" style="font-family: ui-monospace, monospace">'.e($report).'</pre>'))
                    ->placeholder(fn (ScannerGuardAgentDiagnosis $record): string => static::statusLabel($record->status())),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return ScannerGuardPlugin::get()->applyRecordActions($table, [
            ViewAction::make()->modalWidth('5xl'),
            DeleteAction::make(),
        ])
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('filament-scanner-guard::default.diagnoses.requested_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('source')
                    ->label(__('filament-scanner-guard::default.diagnoses.source'))
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip')
                    ->label(__('filament-scanner-guard::default.diagnoses.ip'))
                    ->state(fn (ScannerGuardAgentDiagnosis $record): ?string => static::decrypted($record, 'ip'))
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->label(__('filament-scanner-guard::default.diagnoses.status'))
                    ->state(fn (ScannerGuardAgentDiagnosis $record): string => $record->status())
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::statusLabel($state))
                    ->color(fn (string $state): string => match ($state) {
                        'done' => 'success',
                        'expired' => 'danger',
                        'running' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('verdict')
                    ->label(__('filament-scanner-guard::default.diagnoses.verdict'))
                    ->state(fn (ScannerGuardAgentDiagnosis $record): ?string => static::verdict($record))
                    ->wrap()
                    ->placeholder('—'),
                TextColumn::make('capture')
                    ->label(__('filament-scanner-guard::default.diagnoses.capture'))
                    ->suffix('s')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('completed_at')
                    ->label(__('filament-scanner-guard::default.diagnoses.completed_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            // ponytail: agents answer within a pull (about a minute); 10s shows it soon enough.
            ->poll('10s')
            ->filters([
                SelectFilter::make('source')
                    ->label(__('filament-scanner-guard::default.diagnoses.source'))
                    ->options(fn (): array => ScannerGuardAgentDiagnosis::query()->distinct()->orderBy('source')->pluck('source', 'source')->all()),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function statusLabel(string $status): string
    {
        return __("filament-scanner-guard::default.diagnoses.statuses.{$status}");
    }

    /**
     * The first line of the report's verdict: "BLOCKED on this server by: ..."
     * or "Nothing on this server blocks ...".
     */
    public static function verdict(ScannerGuardAgentDiagnosis $record): ?string
    {
        $report = static::decrypted($record, 'report');

        if ($report === null || ! preg_match('/== verdict ==\s*\n\s*(.+)/', $report, $m)) {
            return null;
        }

        return trim($m[1]);
    }

    /**
     * Null when not stored yet, or encrypted with a key this app no longer has
     * (APP_KEY rotated): that must not take the whole page down.
     */
    public static function decrypted(ScannerGuardAgentDiagnosis $record, string $attribute): ?string
    {
        try {
            return $record->getAttribute($attribute);
        } catch (DecryptException) {
            return null;
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardAgentDiagnoses::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.diagnoses.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.diagnoses.plural_label');
    }
}
