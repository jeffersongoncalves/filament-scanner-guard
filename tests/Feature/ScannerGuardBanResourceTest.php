<?php

use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

/**
 * Mirrors ScannerGuard::ban(): stores the row and bumps its day's stats.
 * Pass $countStats = false to simulate a ban recorded before ban-time counting.
 */
function createBan(array $overrides = [], bool $countStats = true): ScannerGuardBan
{
    $ban = ScannerGuardBan::create(array_merge([
        'ip_hash' => hash('sha256', uniqid()),
        'reason' => ScannerGuardBan::REASON_SCANNER_PATH,
        'matched_value' => 'wp-admin/setup-config.php',
        'hit_count' => 3,
        'banned_at' => now(),
        'expires_at' => now()->addDay(),
    ], $overrides));

    if ($countStats) {
        ScannerGuard::mergeDailyStats($ban->banned_at->toDateString(), [
            'bans_count' => 1,
            'hits_total' => $ban->hit_count,
            'reason_stats' => [$ban->reason => 1],
            'top_matched_values' => [$ban->matched_value => $ban->hit_count],
        ], fn (int $a, int $b): int => $a + $b);
    }

    return $ban;
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

it('defaults the navigation group to the translated label and allows overriding it', function () {
    $plugin = ScannerGuardPlugin::get();

    expect(ScannerGuardBanResource::getNavigationGroup())->toBe(__('filament-scanner-guard::default.navigation.group'))
        ->and(MetricsPage::getNavigationGroup())->toBe(__('filament-scanner-guard::default.navigation.group'));

    $plugin->navigationGroup('Custom Group');

    expect(ScannerGuardBanResource::getNavigationGroup())->toBe('Custom Group')
        ->and(MetricsPage::getNavigationGroup())->toBe('Custom Group');

    $plugin->navigationGroup(null);
});

it('reports ban stats on the metrics widget', function () {
    createBan();
    createBan(['expires_at' => now()->subDay(), 'hit_count' => 5]);

    $getStats = (new ReflectionMethod(StatsOverview::class, 'getStats'));
    $stats = $getStats->invoke(new StatsOverview);

    expect($stats[0]->getValue())->toBe(2)
        ->and($stats[1]->getValue())->toBe(1)
        ->and($stats[2]->getValue())->toBe(1)
        ->and($stats[3]->getValue())->toBe(8)
        ->and($stats[4]->getValue())->toBe(2);
});

it('keeps the recent bans stat after the rows are deleted', function () {
    createBan(['banned_at' => now()->subDays(3)])->delete();
    createBan(['banned_at' => now()->subDays(30)]);

    $stats = (new ReflectionMethod(StatsOverview::class, 'getStats'))->invoke(new StatsOverview);

    expect($stats[4]->getValue())->toBe(1);
});
