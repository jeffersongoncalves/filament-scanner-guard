<?php

use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource\Pages\ListScannerGuardAgents;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use Livewire\Livewire;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgent::class)) {
        $this->markTestSkipped('The installed core has no agent heartbeats (2.1+).');
    }
});

it('hides the agents resource unless the app receives heartbeats', function () {
    expect(ScannerGuardAgentResource::canAccess())->toBeFalse();
});

it('lists agents with their status and filters online / offline', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    $online = ScannerGuardAgent::create([
        'source' => 'web-1',
        'version' => '1.2.0',
        ...(Schema::hasColumn('scanner_guard_agents', 'os') ? ['os' => 'Ubuntu 22.04.5 LTS', 'addresses' => ['10.2.0.251', '203.0.113.5']] : []),
        'rules' => ['sshd', 'nginx'],
        'active_bans' => 7,
        'started_at' => now()->subDay(),
        'last_seen_at' => now()->subMinute(),
    ]);
    $offline = ScannerGuardAgent::create([
        'source' => 'web-2',
        'last_seen_at' => now()->subSeconds(ScannerGuardAgent::STALE_AFTER + 60),
    ]);

    expect(ScannerGuardAgentResource::canAccess())->toBeTrue();

    Livewire::test(ListScannerGuardAgents::class)
        ->assertCanSeeTableRecords([$online, $offline])
        ->assertTableColumnStateSet('status', true, $online)
        ->assertTableColumnStateSet('status', false, $offline)
        ->assertSee(['web-1', '1.2.0', 'sshd', 'nginx'])
        ->assertSee(Schema::hasColumn('scanner_guard_agents', 'os') ? ['Ubuntu 22.04.5 LTS', '10.2.0.251'] : [])
        ->filterTable('status', true)
        ->assertCanSeeTableRecords([$online])
        ->assertCanNotSeeTableRecords([$offline])
        ->filterTable('status', false)
        ->assertCanSeeTableRecords([$offline])
        ->assertCanNotSeeTableRecords([$online]);
});
