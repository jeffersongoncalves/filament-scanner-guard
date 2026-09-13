<?php

use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardServiceProvider;
use Spatie\LaravelPackageTools\Package;

it('can be instantiated', function () {
    $provider = new ScannerGuardServiceProvider(app());

    expect($provider)->toBeInstanceOf(ScannerGuardServiceProvider::class);
});

it('has correct package name', function () {
    $provider = new ScannerGuardServiceProvider(app());

    $package = new Package;
    $provider->configurePackage($package);

    expect($package->name)->toBe('filament-scanner-guard');
});
