<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEventResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEventResource\Pages\ListScannerGuardAgentEvents;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentEvent;
use Livewire\Livewire;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgentEvent::class)) {
        $this->markTestSkipped('The installed core has no agent events (2.6+).');
    }
});

function createAgentEvent(array $overrides = []): ScannerGuardAgentEvent
{
    return ScannerGuardAgentEvent::create(array_merge([
        'source' => 'web-1',
        'rule' => 'ssh_login',
        'ip_hash' => hash('sha256', uniqid()),
        'fields' => ['user' => 'deploy', 'method' => 'publickey', 'key' => 'SHA256:abc', 'port' => '22'],
        'ignored' => false,
        'fingerprint' => hash('sha256', uniqid()),
        'first_seen' => false,
        'occurred_at' => now(),
    ], $overrides));
}

it('hides the agent events resource unless the app receives events', function () {
    expect(ScannerGuardAgentEventResource::canAccess())->toBeFalse();
});

it('hides the agent events resource until its migration ran', function () {
    config()->set('scanner-guard.http.server.enabled', true);
    Schema::drop((new ScannerGuardAgentEvent)->getTable());

    expect(ScannerGuardAgentEventResource::canAccess())->toBeFalse();
});

it('lists agent events with their fields, the stored ip and badges', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    $new = createAgentEvent(['ip' => '203.0.113.9', 'first_seen' => true, 'occurred_at' => now()->subMinute()]);
    $ignored = createAgentEvent(['source' => 'web-2', 'rule' => 'sudo', 'fields' => ['user' => 'root'], 'ignored' => true, 'occurred_at' => now()->subHour()]);

    expect(ScannerGuardAgentEventResource::canAccess())->toBeTrue();

    Livewire::test(ListScannerGuardAgentEvents::class)
        ->assertCanSeeTableRecords([$new, $ignored], inOrder: true)
        ->assertTableColumnStateSet('fields', 'user=deploy method=publickey key=SHA256:abc', $new)
        ->assertTableColumnStateSet('ip', '203.0.113.9', $new)
        ->assertTableColumnStateSet('ip', null, $ignored)
        ->assertTableColumnStateSet('first_seen', 'New', $new)
        ->assertTableColumnStateSet('first_seen', null, $ignored)
        ->assertTableColumnStateSet('ignored', 'Ignored', $ignored);
});

it('still lists an event whose ip can no longer be decrypted', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    $event = createAgentEvent(['ip' => '203.0.113.9']);
    DB::table('scanner_guard_agent_events')->update(['ip' => 'encrypted-with-a-lost-key']);

    Livewire::test(ListScannerGuardAgentEvents::class)
        ->assertTableColumnStateSet('ip', null, $event->fresh());
});

it('filters agent events by source, rule, first seen and ignored', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    $login = createAgentEvent(['first_seen' => true]);
    $sudo = createAgentEvent(['source' => 'web-2', 'rule' => 'sudo', 'ignored' => true]);

    Livewire::test(ListScannerGuardAgentEvents::class)
        ->filterTable('source', 'web-2')
        ->assertCanSeeTableRecords([$sudo])
        ->assertCanNotSeeTableRecords([$login])
        ->resetTableFilters()
        ->filterTable('rule', 'ssh_login')
        ->assertCanSeeTableRecords([$login])
        ->assertCanNotSeeTableRecords([$sudo])
        ->resetTableFilters()
        ->filterTable('first_seen', true)
        ->assertCanSeeTableRecords([$login])
        ->assertCanNotSeeTableRecords([$sudo])
        ->resetTableFilters()
        ->filterTable('ignored', true)
        ->assertCanSeeTableRecords([$sudo])
        ->assertCanNotSeeTableRecords([$login]);
});
