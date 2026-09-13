<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

function createBan(array $overrides = []): ScannerGuardBan
{
    return ScannerGuardBan::create(array_merge([
        'ip_hash' => hash('sha256', uniqid()),
        'reason' => ScannerGuardBan::REASON_SCANNER_PATH,
        'matched_value' => 'wp-admin/setup-config.php',
        'hit_count' => 3,
        'banned_at' => now(),
        'expires_at' => now()->addDay(),
    ], $overrides));
}

it('registers the resource in the plugin', function () {
    expect(ScannerGuardPlugin::make()->getId())->toBe('filament-scanner-guard');
});

it('flags active vs expired bans', function () {
    $active = createBan();
    $expired = createBan(['expires_at' => now()->subDay()]);

    expect($active->fresh()->is_active)->toBeTrue();
    expect($expired->fresh()->is_active)->toBeFalse();
});

it('scopes only active bans', function () {
    $active = createBan();
    $expired = createBan(['expires_at' => now()->subDay()]);

    expect(ScannerGuardBan::active()->pluck('id'))->toContain($active->id)
        ->not->toContain($expired->id);
});

it('unbans by deleting the record', function () {
    $ban = createBan();

    $ban->delete();

    expect(ScannerGuardBan::find($ban->id))->toBeNull();
});

it('uses the correct model for the resource', function () {
    expect(ScannerGuardBanResource::getModel())->toBe(ScannerGuardBan::class);
});

it('does not allow creating records', function () {
    expect(ScannerGuardBanResource::canCreate())->toBeFalse();
});
