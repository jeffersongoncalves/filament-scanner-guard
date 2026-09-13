<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Tests;

use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardServiceProvider;
use JeffersonGoncalves\Filament\ScannerGuard\Tests\Fixtures\TestPanelProvider;
use JeffersonGoncalves\ScannerGuard\ScannerGuardServiceProvider as LaravelScannerGuardServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            FilamentServiceProvider::class,
            TestPanelProvider::class,
            LaravelScannerGuardServiceProvider::class,
            ScannerGuardServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }

    protected function defineDatabaseMigrations(): void
    {
        // laravel-scanner-guard ships its migration as a .stub (not auto-run
        // by package-tools without ->runsMigrations()), so copy it to a real
        // migration file the same way its own test suite does.
        $tempPath = sys_get_temp_dir().'/filament-scanner-guard-migrations';

        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0755, true);
        }

        copy(
            __DIR__.'/../vendor/jeffersongoncalves/laravel-scanner-guard/database/migrations/create_scanner_guard_bans_table.php.stub',
            $tempPath.'/0001_01_01_000000_create_scanner_guard_bans_table.php'
        );

        $this->loadMigrationsFrom($tempPath);
    }
}
