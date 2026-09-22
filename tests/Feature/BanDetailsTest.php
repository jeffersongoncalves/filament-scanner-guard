<?php

use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\PurgeExpiredBansAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;

it('registers a view page for the resource', function () {
    expect(ScannerGuardBanResource::getPages())
        ->toHaveKey('view')
        ->and(ScannerGuardBanResource::canEdit(createBan()))->toBeFalse();
});

it('builds the extend and purge actions with default names', function () {
    expect(ExtendBanAction::make()->getName())->toBe('extend')
        ->and(PurgeExpiredBansAction::make()->getName())->toBe('purgeExpired');
});

it('extends an active ban expiry from the current expiry', function () {
    $ban = createBan(['expires_at' => now()->addDay()]);
    $originalExpiry = $ban->expires_at->copy();

    $action = ExtendBanAction::make();
    $runAction = (new ReflectionMethod($action, 'getAction'))->invoke($action);

    // Simulate the action callback with a 1-week duration.
    $runAction(['duration' => 604800], $ban, $action);

    expect($ban->fresh()->expires_at->equalTo($originalExpiry->addWeek()))->toBeTrue();
});

it('purges only expired bans', function () {
    $active = createBan();
    $expired = createBan(['expires_at' => now()->subDay()]);

    $action = PurgeExpiredBansAction::make();
    $runAction = (new ReflectionMethod($action, 'getAction'))->invoke($action);
    $runAction([], $action);

    expect(\JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan::find($active->id))->not->toBeNull()
        ->and(\JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan::find($expired->id))->toBeNull();
});

it('exposes view page header actions', function () {
    $getHeaderActions = (new ReflectionMethod(ViewScannerGuardBan::class, 'getHeaderActions'));

    $names = collect($getHeaderActions->invoke(new ViewScannerGuardBan))
        ->map(fn ($action) => $action->getName())
        ->all();

    expect($names)->toContain('extend', 'unban');
});
