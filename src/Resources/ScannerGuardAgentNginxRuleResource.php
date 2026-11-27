<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRuleResource\Pages\ManageScannerGuardAgentNginxRules;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentNginxRule;

/**
 * The optional nginx rules of scanner-guard-agent (cms, rfi, encoded,
 * empty-agent), turned on for every server but the exceptions (core 2.8+).
 * Only the name reaches the agents: the rules ship with the agent.
 */
class ScannerGuardAgentNginxRuleResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardAgentNginxRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return ScannerGuardPlugin::servesAgentNginxRules() && parent::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('name')
                    ->label(__('filament-scanner-guard::default.nginx_rules.name'))
                    ->options(fn (?ScannerGuardAgentNginxRule $record): array => static::nameOptions($record))
                    ->helperText(fn (?string $state): ?string => static::describe($state))
                    ->live()
                    ->required()
                    ->in(array_keys(ScannerGuardAgentNginxRule::NAMES))
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
                Toggle::make('enabled')
                    ->label(__('filament-scanner-guard::default.nginx_rules.enabled'))
                    ->default(true),
                Select::make('except')
                    ->label(__('filament-scanner-guard::default.nginx_rules.except'))
                    ->helperText(__('filament-scanner-guard::default.nginx_rules.help.except'))
                    ->multiple()
                    ->options(fn (): array => static::sources()),
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
                    ->label(__('filament-scanner-guard::default.nginx_rules.name'))
                    ->badge()
                    ->color('gray')
                    ->description(fn (ScannerGuardAgentNginxRule $record): ?string => static::describe($record->name))
                    ->sortable(),
                ToggleColumn::make('enabled')
                    // The column saves without asking the policy: only for who may edit the rule.
                    ->disabled(fn (ScannerGuardAgentNginxRule $record): bool => ! static::canEdit($record))
                    ->label(__('filament-scanner-guard::default.nginx_rules.enabled')),
                TextColumn::make('except')
                    ->label(__('filament-scanner-guard::default.nginx_rules.except'))
                    ->badge()
                    ->color('warning')
                    ->placeholder(__('filament-scanner-guard::default.nginx_rules.none')),
                TextColumn::make('updated_at')
                    ->label(__('filament-scanner-guard::default.agent_rules.updated_at'))
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->defaultSort('name');
    }

    /**
     * What the rule blocks, translated; the core's English text for a name
     * newer than this plugin.
     */
    public static function describe(?string $name): ?string
    {
        if ($name === null || ! array_key_exists($name, ScannerGuardAgentNginxRule::NAMES)) {
            return null;
        }

        $key = "filament-scanner-guard::default.nginx_rules.descriptions.{$name}";

        return __($key) === $key ? ScannerGuardAgentNginxRule::NAMES[$name] : __($key);
    }

    /**
     * The rules the agent knows that have no row yet (plus the record's own).
     *
     * @return array<string, string>
     */
    public static function nameOptions(?ScannerGuardAgentNginxRule $record): array
    {
        $taken = ScannerGuardAgentNginxRule::query()->whereKeyNot($record?->getKey())->pluck('name')->all();
        $names = array_diff(array_keys(ScannerGuardAgentNginxRule::NAMES), $taken);

        return array_combine($names, $names);
    }

    /**
     * Servers that sent a heartbeat.
     *
     * @return array<string, string>
     */
    public static function sources(): array
    {
        if (! ScannerGuardPlugin::receivesHeartbeats()) {
            return [];
        }

        return ScannerGuardAgent::query()->orderBy('source')->pluck('source', 'source')->all();
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageScannerGuardAgentNginxRules::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.nginx_rules.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.nginx_rules.plural_label');
    }
}
