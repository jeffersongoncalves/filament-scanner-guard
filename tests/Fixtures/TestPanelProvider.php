<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->plugin(ScannerGuardPlugin::make());
    }
}
