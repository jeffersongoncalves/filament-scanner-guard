<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules;

use Closure;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\Pages\ManageScannerGuardAgentRules;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentRule;

/**
 * Threshold, window and ban steps of a scanner-guard-agent rule, set once
 * for every server (core 2.5+). The agents pick a change up on their next pull.
 */
class ScannerGuardAgentRuleResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardAgentRule::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return ScannerGuardPlugin::servesAgentRules() && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament-scanner-guard::default.agent_rules.name'))
                    ->helperText(__('filament-scanner-guard::default.agent_rules.help.name'))
                    ->datalist(static::knownRuleNames())
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true)
                    ->dehydrateStateUsing(fn (?string $state): string => trim((string) $state)),
                TextInput::make('threshold')
                    ->label(__('filament-scanner-guard::default.agent_rules.threshold'))
                    ->helperText(__('filament-scanner-guard::default.agent_rules.help.threshold'))
                    ->integer()
                    ->required()
                    ->minValue(1)
                    ->maxValue(ScannerGuardAgentRule::MAX_THRESHOLD),
                TextInput::make('window')
                    ->label(__('filament-scanner-guard::default.agent_rules.window'))
                    ->helperText(__('filament-scanner-guard::default.agent_rules.help.window'))
                    ->placeholder('10m')
                    ->required()
                    ->maxLength(20)
                    ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        $seconds = ScannerGuardAgentRule::parseDuration((string) $value);

                        if ($seconds === null || $seconds > ScannerGuardAgentRule::MAX_WINDOW) {
                            $fail(__('filament-scanner-guard::default.agent_rules.invalid_window'));
                        }
                    })
                    ->dehydrateStateUsing(fn (?string $state): string => trim((string) $state)),
                TextInput::make('ban')
                    ->label(__('filament-scanner-guard::default.agent_rules.ban'))
                    ->helperText(__('filament-scanner-guard::default.agent_rules.help.ban'))
                    ->placeholder('1d, 7d, 30d')
                    ->required()
                    ->maxLength(255)
                    ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        if (ScannerGuardAgentRule::parseSteps((string) $value) === null) {
                            $fail(__('filament-scanner-guard::default.agent_rules.invalid_ban'));
                        }
                    })
                    ->dehydrateStateUsing(fn (?string $state): string => implode(', ', array_map(trim(...), explode(',', (string) $state)))),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return ScannerGuardPlugin::get()->applyRecordActions($table, [
            EditAction::make(),
            DeleteAction::make(),
        ])
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-scanner-guard::default.agent_rules.name'))
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('threshold')
                    ->label(__('filament-scanner-guard::default.agent_rules.threshold'))
                    ->numeric(),
                TextColumn::make('window')
                    ->label(__('filament-scanner-guard::default.agent_rules.window'))
                    ->fontFamily('mono'),
                TextColumn::make('ban')
                    ->label(__('filament-scanner-guard::default.agent_rules.ban'))
                    ->fontFamily('mono'),
                TextColumn::make('updated_at')
                    ->label(__('filament-scanner-guard::default.agent_rules.updated_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * Rule names the agents reported in their heartbeats, as suggestions.
     *
     * @return list<string>
     */
    public static function knownRuleNames(): array
    {
        if (! ScannerGuardPlugin::receivesHeartbeats()) {
            return [];
        }

        return ScannerGuardAgent::query()->pluck('rules')->flatten()->filter(fn (mixed $name): bool => is_string($name))->unique()->sort()->values()->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageScannerGuardAgentRules::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.agent_rules.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.agent_rules.plural_label');
    }
}
