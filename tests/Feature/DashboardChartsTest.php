<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansPerDayChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\TopMatchedValuesChart;

it('exposes the bans-per-day chart as a line chart with 14 labels', function () {
    createBan(['banned_at' => now()->subDays(2)]);
    createBan(['banned_at' => now()->subDays(2)]);
    createBan();

    $widget = new BansPerDayChart;
    $getData = (new ReflectionMethod(BansPerDayChart::class, 'getData'));
    $getType = (new ReflectionMethod(BansPerDayChart::class, 'getType'));

    $data = $getData->invoke($widget);

    expect($getType->invoke($widget))->toBe('line')
        ->and($data['labels'])->toHaveCount(14)
        ->and($data['datasets'][0]['data'])->toHaveCount(14)
        ->and(array_sum($data['datasets'][0]['data']))->toBe(3);
});

it('groups bans by reason in the doughnut chart', function () {
    createBan(['reason' => \JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan::REASON_SCANNER_PATH]);
    createBan(['reason' => \JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan::REASON_ASN]);

    $widget = new BansByReasonChart;
    $getData = (new ReflectionMethod(BansByReasonChart::class, 'getData'));
    $getType = (new ReflectionMethod(BansByReasonChart::class, 'getType'));

    $data = $getData->invoke($widget);

    expect($getType->invoke($widget))->toBe('doughnut')
        ->and($data['datasets'][0]['data'])->toBe([1, 1])
        ->and($data['labels'])->toHaveCount(2);
});

it('lists top matched values ordered by total hits', function () {
    createBan(['matched_value' => 'wp-login.php', 'hit_count' => 10]);
    createBan(['matched_value' => 'xmlrpc.php', 'hit_count' => 4]);

    $widget = new TopMatchedValuesChart;
    $getData = (new ReflectionMethod(TopMatchedValuesChart::class, 'getData'));
    $getType = (new ReflectionMethod(TopMatchedValuesChart::class, 'getType'));

    $data = $getData->invoke($widget);

    expect($getType->invoke($widget))->toBe('bar')
        ->and($data['labels'][0])->toBe('wp-login.php')
        ->and($data['datasets'][0]['data'][0])->toBe(10);
});
