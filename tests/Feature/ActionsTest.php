<?php

use JeffersonGoncalves\Filament\ScannerGuard\Actions\PurgeExpiredBansAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;

it('builds the unban action with the default name', function () {
    expect(UnbanAction::make()->getName())->toBe('unban');
});

it('builds the unban bulk action with the default name', function () {
    expect(UnbanBulkAction::make()->getName())->toBe('unban');
});

it('shows purge expired unless the plugin turns it off', function () {
    expect(PurgeExpiredBansAction::make()->isVisible())->toBeTrue();

    ScannerGuardPlugin::get()->purgeExpiredBans(false);

    expect(PurgeExpiredBansAction::make()->isVisible())->toBeFalse();
});
