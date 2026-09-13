<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans;

use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

class ScannerGuardBanResource extends Resource
{
    protected static ?string $model = ScannerGuardBan::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static ?string $recordTitleAttribute = 'ip_hash';

    public static function table(Table $table): Table
    {
        return ScannerGuardBansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScannerGuardBans::route('/'),
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
