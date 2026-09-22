<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans;

use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Concerns\HasPluginNavigationGroup;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Schemas\ScannerGuardBanInfolist;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBanResource extends Resource
{
    use HasPluginNavigationGroup;

    protected static ?string $model = ScannerGuardBan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static ?string $recordTitleAttribute = 'ip_hash';

    public static function table(Table $table): Table
    {
        return ScannerGuardBansTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ScannerGuardBanInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardBans::route('/'),
            'view' => ViewScannerGuardBan::route('/{record}'),
        ];
    }

    public static function getRecordActions(): array
    {
        return [
            ViewAction::make(),
            ExtendBanAction::make(),
            UnbanAction::make(),
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

    public static function canEdit($record): bool
    {
        return false;
    }
}

