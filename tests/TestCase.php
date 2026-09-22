<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardServiceProvider;
use JeffersonGoncalves\Filament\ScannerGuard\Tests\Fixtures\TestPanelProvider;
use JeffersonGoncalves\ScannerGuard\ScannerGuardServiceProvider as LaravelScannerGuardServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            // Must register before Livewire: it rebinds Livewire's DataStore.
            SupportServiceProvider::class,
            LivewireServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
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
