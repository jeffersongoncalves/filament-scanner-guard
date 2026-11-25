<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBlocklistEntry;

class ScannerGuardBlocklistResource extends Resource
{
    use HasPluginNavigationGroup;

    /** ScannerGuardBlocklistEntry::TYPE_IGNORE, missing from a core older than 2.3. */
    public const TYPE_IGNORE = 'ignore';

    protected static ?string $model = ScannerGuardBlocklistEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?string $recordTitleAttribute = 'value';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label(__('filament-scanner-guard::default.blocklist.type'))
                    ->options(static::typeOptions())
                    ->default(ScannerGuardBlocklistEntry::TYPE_IP)
                    ->required()
                    ->live(),
                TextInput::make('value')
                    ->label(__('filament-scanner-guard::default.blocklist.value'))
                    ->helperText(fn (Get $get): string => match ($get('type')) {
                        ScannerGuardBlocklistEntry::TYPE_USER_AGENT => __('filament-scanner-guard::default.blocklist.help.user_agent'),
                        self::TYPE_IGNORE => __('filament-scanner-guard::default.blocklist.help.ignore'),
                        default => __('filament-scanner-guard::default.blocklist.help.ip'),
                    })
                    ->required()
                    ->maxLength(255)
                    ->rule(fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                        $entry = trim((string) $value);

                        if (in_array($get('type'), [ScannerGuardBlocklistEntry::TYPE_IP, self::TYPE_IGNORE], true) && ! static::isValidRange($entry)) {
                            $fail(__('filament-scanner-guard::default.blocklist.invalid_ip'));
                        } elseif ($get('type') === self::TYPE_IGNORE && static::isTooBroad($entry)) {
                            $fail(__('filament-scanner-guard::default.blocklist.too_broad'));
                        }
                    })
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('type', $get('type')))
                    ->dehydrateStateUsing(fn (?string $state): string => trim((string) $state)),
                TextInput::make('note')
                    ->label(__('filament-scanner-guard::default.blocklist.note'))
                    ->maxLength(255),
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
                TextColumn::make('type')
                    ->label(__('filament-scanner-guard::default.blocklist.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::typeOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        ScannerGuardBlocklistEntry::TYPE_IP => 'danger',
                        self::TYPE_IGNORE => 'success',
                        default => 'warning',
                    }),
                TextColumn::make('value')
                    ->label(__('filament-scanner-guard::default.blocklist.value'))
                    ->fontFamily('mono')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('note')
                    ->label(__('filament-scanner-guard::default.blocklist.note'))
                    ->searchable()
                    ->limit(60)
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label(__('filament-scanner-guard::default.blocklist.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label(__('filament-scanner-guard::default.blocklist.type'))
                    ->options(static::typeOptions()),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * @return array<string, string>
     */
    public static function typeOptions(): array
    {
        return [
            ScannerGuardBlocklistEntry::TYPE_IP => __('filament-scanner-guard::default.blocklist.types.ip'),
            ScannerGuardBlocklistEntry::TYPE_USER_AGENT => __('filament-scanner-guard::default.blocklist.types.user_agent'),
            ...(static::supportsIgnore() ? [self::TYPE_IGNORE => __('filament-scanner-guard::default.blocklist.types.ignore')] : []),
        ];
    }

    /**
     * Core 2.3+: an older one would store the entry and never apply it.
     */
    public static function supportsIgnore(): bool
    {
        return defined(ScannerGuardBlocklistEntry::class.'::TYPE_IGNORE');
    }

    /**
     * Same limit as the core's ipIgnore(), which drops such ranges.
     */
    public static function isTooBroad(string $entry): bool
    {
        $prefix = explode('/', $entry, 2)[1] ?? null;

        return $prefix !== null && (int) $prefix < (str_contains($entry, ':') ? 16 : 8);
    }

    /**
     * An IPv4/IPv6 address, optionally with a /prefix in range: what nginx
     * accepts in `deny`. Same check as the core's denylist (public there only since 2.0).
     */
    public static function isValidRange(string $entry): bool
    {
        [$address, $prefix] = array_pad(explode('/', $entry, 2), 2, null);

        if (filter_var($address, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return $prefix === null
            || (ctype_digit($prefix) && (int) $prefix <= (str_contains((string) $address, ':') ? 128 : 32));
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageScannerGuardBlocklist::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-scanner-guard::default.blocklist.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-scanner-guard::default.blocklist.plural_label');
    }
}
