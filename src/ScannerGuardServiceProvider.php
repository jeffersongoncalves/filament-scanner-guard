<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScannerGuardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-scanner-guard')
            ->hasTranslations()
            ->hasViews();
    }
}
