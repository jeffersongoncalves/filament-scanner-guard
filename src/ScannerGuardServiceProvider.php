<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use JeffersonGoncalves\Filament\ScannerGuard\Support\Denylist;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

class ScannerGuardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-scanner-guard')
            ->hasMigration('create_scanner_guard_blocklist_table')
            ->hasTranslations()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        static::applyBlocklist();

        // A long-running worker booted before a panel change: refresh so the
        // on-ban export doesn't drop the newer entries.
        Event::listen(JobProcessing::class, fn () => static::applyBlocklist());
    }

    protected static function applyBlocklist(): void
    {
        try {
            Denylist::apply();
        } catch (Throwable) {
            // Table not migrated yet, or no database/cache during deploy: config lists still apply.
        }
    }
}
