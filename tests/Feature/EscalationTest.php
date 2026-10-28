<?php

use Filament\Panel;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\ExtendBanAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ListScannerGuardBans;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Pages\ViewScannerGuardBan;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\BansByReasonChart;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Widgets\StatsOverview;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use Livewire\Livewire;

const LADDER = [86400, 604800, 1209600];

it('labels and charts 404 flood bans', function () {
    $ban = createBan(['reason' => ScannerGuardBan::REASON_NOT_FOUND_FLOOD]);
    createBan(['reason' => ScannerGuardBan::REASON_ASN]);

    Livewire::test(ListScannerGuardBans::class)
        ->assertSee(__('filament-scanner-guard::default.reason.not_found_flood'));

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->assertSee(__('filament-scanner-guard::default.reason.not_found_flood'));

    expect(chartData(BansByReasonChart::class)['datasets'][0]['data'])->toBe([0, 1, 1]);
});

it('hides offense column, filter and stat while escalation is off', function () {
    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnHidden('offense')
        ->assertTableFilterHidden('repeat_offenders');

    $stats = (new ReflectionMethod(StatsOverview::class, 'getStats'))->invoke(new StatsOverview);

    expect($stats)->toHaveCount(5);
});

it('shows offenses and filters repeat offenders with escalation on', function () {
    config()->set('scanner-guard.escalation', LADDER);

    $first = createBan(['offense' => 1]);
    $repeat = createBan(['offense' => 3]);
    // Same IP banned twice (e.g. via two satellites): one offender, not two.
    createBan(['offense' => 2, 'ip_hash' => $repeat->ip_hash]);
    createBan(['offense' => 2, 'expires_at' => now()->subDay()]);

    Livewire::test(ListScannerGuardBans::class)
        ->assertTableColumnVisible('offense')
        ->filterTable('repeat_offenders')
        ->assertCanSeeTableRecords([$repeat])
        ->assertCanNotSeeTableRecords([$first]);

    $stats = (new ReflectionMethod(StatsOverview::class, 'getStats'))->invoke(new StatsOverview);

    expect($stats[5]->getValue())->toBe(1);
});

it('suggests the next escalation step when extending', function () {
    expect(ExtendBanAction::suggestedDuration(createBan(['offense' => 1])))->toBe(86400);

    config()->set('scanner-guard.escalation', LADDER);

    expect(ExtendBanAction::suggestedDuration(createBan()))->toBe(86400)
        ->and(ExtendBanAction::suggestedDuration(createBan(['offense' => 1])))->toBe(604800)
        ->and(ExtendBanAction::suggestedDuration(createBan(['offense' => 5])))->toBe(1209600)
        ->and(ExtendBanAction::durationOptions())->toHaveKeys([3600, 86400, 604800, 1209600, 2592000]);
});

it('extends by the suggested step by default', function () {
    config()->set('scanner-guard.escalation', LADDER);
    $ban = createBan(['offense' => 2, 'expires_at' => now()->addHour()]);
    $expected = $ban->expires_at->copy()->addSeconds(1209600);

    Livewire::test(ViewScannerGuardBan::class, ['record' => $ban->getKey()])
        ->callAction('extend')
        ->assertHasNoActionErrors();

    expect($ban->fresh()->expires_at->timestamp)->toBe($expected->timestamp);
});

it('registers nothing when the driver keeps no ban rows', function () {
    config()->set('scanner-guard.driver', 'cache');

    $panel = Panel::make()->id('cache-only');
    ScannerGuardPlugin::make()->register($panel);

    expect($panel->getResources())->toBeEmpty()
        ->and($panel->getPages())->toBeEmpty();
});
