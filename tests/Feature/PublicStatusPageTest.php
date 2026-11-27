<?php

use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentEvent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentMetric;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgentMetric::class)) {
        $this->markTestSkipped('The installed core has no agent metrics (2.6+).');
    }
});

it('is not routed unless the panel enables it', function () {
    $this->get('/admin/scanner-guard-status')->assertNotFound();
});

it('shows each server without login and never an ip, user or log line', function () {
    ScannerGuardAgent::create(['source' => 'web-1', 'version' => '2.6.0', 'active_bans' => 42, 'last_seen_at' => now()]);
    ScannerGuardAgent::create(['source' => 'db-1', 'version' => '2.5.0', 'active_bans' => 40, 'last_seen_at' => now()->subDay()]);
    ScannerGuardAgentMetric::create(['source' => 'web-1', 'rule' => 'web_attack', 'hour' => now()->startOfHour(), 'matches' => 1200]);
    ScannerGuardAgentMetric::create(['source' => 'web-1', 'rule' => 'ssh_bruteforce', 'hour' => now()->startOfHour()->subHours(3), 'matches' => 34]);
    ScannerGuardAgentMetric::create(['source' => 'web-1', 'rule' => 'web_attack', 'hour' => now()->subDays(2), 'matches' => 999999]);
    $ban = createBan(['reason' => 'ssh_bruteforce', 'ip' => '198.51.100.7']);
    ScannerGuardAgentEvent::create([
        'source' => 'web-1',
        'rule' => 'ssh_login',
        'ip_hash' => 'f00dfeedhash',
        'ip' => '203.0.113.9',
        'fields' => ['user' => 'deploy-user', 'key' => 'SHA256:secretkey'],
        'line' => 'Accepted publickey for deploy-user from <ip>',
        'fingerprint' => 'fp',
        'first_seen' => true,
        'occurred_at' => now(),
    ]);

    $this->get('/status/servers')
        ->assertOk()
        ->assertSee(['Server status', 'web-1', 'db-1', '2.6.0', 'Online', 'Offline', '1 / 2', '1,234', 'web_attack: 1,200', 'ssh_bruteforce'])
        ->assertSeeInOrder(['IPs blocked now', '42'])
        ->assertSee('wire:poll.60s', escape: false)
        ->assertDontSee('999,999')
        ->assertDontSee(['203.0.113.9', '198.51.100.7', $ban->ip_hash, 'f00dfeedhash', 'deploy-user', 'SHA256:secretkey', 'Accepted publickey']);
});

it('degrades without the agents and metrics tables', function () {
    Schema::drop((new ScannerGuardAgentMetric)->getTable());
    Schema::drop((new ScannerGuardAgent)->getTable());
    createBan();

    $this->get('/status/servers')
        ->assertOk()
        ->assertSee(['No server has reported yet.', 'Scanner Path']);
});
