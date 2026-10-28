<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

class ScannerGuardBanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-scanner-guard::default.infolist.sections.ban_details'))
                    ->columns(2)
                    ->schema([
                        TextEntry::make('ip_hash')
                            ->label(__('filament-scanner-guard::default.table.ip_hash'))
                            ->copyable()
                            ->fontFamily('mono'),
                        TextEntry::make('reason')
                            ->label(__('filament-scanner-guard::default.table.reason'))
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ScannerGuardBansTable::reasonLabel($state))
                            ->color(fn (string $state): string => ScannerGuardBansTable::reasonColor($state)),
                        TextEntry::make('matched_value')
                            ->label(__('filament-scanner-guard::default.table.matched_value'))
                            ->copyable()
                            ->columnSpanFull(),
                        TextEntry::make('hit_count')
                            ->label(__('filament-scanner-guard::default.table.hit_count'))
                            ->numeric(),
                        TextEntry::make('offense')
                            ->label(__('filament-scanner-guard::default.table.offense'))
                            ->badge()
                            ->color(fn (int $state): string => $state > 1 ? 'danger' : 'gray')
                            ->placeholder('—')
                            ->visible(fn (): bool => ScannerGuardPlugin::escalationEnabled()),
                        IconEntry::make('is_active')
                            ->label(__('filament-scanner-guard::default.table.is_active'))
                            ->boolean(),
                        TextEntry::make('banned_at')
                            ->label(__('filament-scanner-guard::default.table.banned_at'))
                            ->dateTime(),
                        TextEntry::make('expires_at')
                            ->label(__('filament-scanner-guard::default.table.expires_at'))
                            ->dateTime(),
                    ]),
            ]);
    }
}
