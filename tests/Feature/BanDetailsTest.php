<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use Livewire\Livewire;

it('renders the view page for a ban', function () {
    $ban = createBan(['matched_value' => '.env']);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertOk()
        ->assertSee('.env');
});

it('extends an active ban from its current expiry', function () {
    $ban = createBan(['expires_at' => now()->addDay()]);
    $expected = $ban->expires_at->copy()->addWeek();

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->callAction('extend', ['duration' => 604800])
        ->assertHasNoActionErrors();

    expect($ban->fresh()->expires_at->timestamp)->toBe($expected->timestamp);
});

it('redirects to the list after unbanning from the view page', function () {
    $ban = createBan();

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->callAction('unban')
        ->assertRedirect(ScannerGuardBanResource::getUrl('index'));

    expect(ScannerGuardBan::find($ban->getKey()))->toBeNull();
});

it('purges only expired bans', function () {
    $active = createBan();
    $expired = createBan(['expires_at' => now()->subDay()]);

    Livewire::test(ListScannerGuardBans::class)
        ->callAction('purgeExpired');

    expect(ScannerGuardBan::find($active->getKey()))->not->toBeNull()
        ->and(ScannerGuardBan::find($expired->getKey()))->toBeNull();
});
