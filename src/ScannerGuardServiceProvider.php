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

        // Long-running workers (queue, Octane) booted before a panel change:
        // re-merge per job/request so they never keep stale entries. The
        // Octane event is a string, so Octane stays an optional dependency.
        Event::listen([JobProcessing::class, 'Laravel\Octane\Events\RequestReceived'], fn () => static::applyBlocklist());
    }

    protected static function applyBlocklist(): void
    {
        try {
            Denylist::apply();
        } catch (Throwable $e) {
            // Table not migrated yet, or no database/cache: config lists still apply, but say so.
            report($e);
        }
    }
}
