<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

class StatusPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('status')
            ->path('status')
            ->login()
            ->plugin(ScannerGuardPlugin::make()->publicStatusPage(path: 'servers'));
    }
}
