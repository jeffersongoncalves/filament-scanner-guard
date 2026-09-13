<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Concerns;

use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

/**
 * Every resource/page this plugin registers shares one navigation group so
 * they cluster together in the sidebar. ScannerGuardPlugin::navigationGroup()
 * overrides it panel-wide; unset, it falls back to a translated default.
 */
trait HasPluginNavigationGroup
{
    public static function getNavigationGroup(): ?string
    {
        return ScannerGuardPlugin::get()->getNavigationGroup();
    }
}
