<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklist;

use Closure;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Models\ScannerGuardBlocklistEntry;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklist\Pages\ManageScannerGuardBlocklist;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;

class ScannerGuardBlocklistResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardBlocklistEntry::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedNoSymbol;

    protected static ?string $recordTitleAttribute = 'value';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label(__('filament-scanner-guard::default.blocklist.type'))
                    ->options(static::typeOptions())
                    ->default(ScannerGuardBlocklistEntry::TYPE_IP)
                    ->required()
                    ->live(),
                TextInput::make('value')
                    ->label(__('filament-scanner-guard::default.blocklist.value'))
                    ->helperText(fn (Get $get): string => $get('type') === ScannerGuardBlocklistEntry::TYPE_USER_AGENT
                        ? __('filament-scanner-guard::default.blocklist.help.user_agent')
                        : __('filament-scanner-guard::default.blocklist.help.ip'))
                    ->required()
                    ->maxLength(255)
                    ->rule(fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                        if ($get('type') === ScannerGuardBlocklistEntry::TYPE_IP && ! Denylist::isValidRange(trim((string) $value))) {
                            $fail(__('filament-scanner-guard::default.blocklist.invalid_ip'));
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
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label(__('filament-scanner-guard::default.blocklist.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::typeOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => $state === ScannerGuardBlocklistEntry::TYPE_IP ? 'danger' : 'warning'),
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
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
        ];
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
