<?php

use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\UnbanBulkAction;

it('builds the unban action with the default name', function () {
    expect(UnbanAction::make()->getName())->toBe('unban');
});

it('builds the unban bulk action with the default name', function () {
    expect(UnbanBulkAction::make()->getName())->toBe('unban');
});
