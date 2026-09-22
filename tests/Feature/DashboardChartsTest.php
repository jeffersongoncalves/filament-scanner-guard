<?php

use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansPerDayChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\TopMatchedValuesChart;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use Livewire\Livewire;

use function Livewire\invade;

function chartData(string $widget): array
{
    return invade(Livewire::test($widget)->assertOk()->instance())->getCachedData();
}

it('shows the charts on the metrics page', function () {
    Livewire::test(MetricsPage::class)
        ->assertOk()
        ->assertSeeLivewire(BansPerDayChart::class)
        ->assertSeeLivewire(BansByReasonChart::class)
        ->assertSeeLivewire(TopMatchedValuesChart::class);
});

it('counts bans per day over the last 14 days', function () {
    createBan(['banned_at' => now()->subDays(2)]);
    createBan(['banned_at' => now()->subDays(2)]);
    createBan();
    createBan(['banned_at' => now()->subDays(30)]);

    $data = chartData(BansPerDayChart::class);

    expect($data['labels'])->toHaveCount(14)
        ->and($data['datasets'][0]['data'])->toHaveCount(14)
        ->and($data['datasets'][0]['data'][11])->toBe(2)
        ->and($data['datasets'][0]['data'][13])->toBe(1)
        ->and(array_sum($data['datasets'][0]['data']))->toBe(3);
});

it('groups bans by reason', function () {
    createBan(['reason' => ScannerGuardBan::REASON_SCANNER_PATH]);
    createBan(['reason' => ScannerGuardBan::REASON_SCANNER_PATH]);
    createBan(['reason' => ScannerGuardBan::REASON_ASN]);

    expect(chartData(BansByReasonChart::class)['datasets'][0]['data'])->toBe([2, 1]);
});

it('lists top matched values ordered by total hits', function () {
    createBan(['matched_value' => 'xmlrpc.php', 'hit_count' => 4]);
    createBan(['matched_value' => 'wp-login.php', 'hit_count' => 6]);
    createBan(['matched_value' => 'wp-login.php', 'hit_count' => 4]);

    $data = chartData(TopMatchedValuesChart::class);

    expect($data['labels'])->toBe(['wp-login.php', 'xmlrpc.php'])
        ->and($data['datasets'][0]['data'])->toBe([10, 4]);
});
