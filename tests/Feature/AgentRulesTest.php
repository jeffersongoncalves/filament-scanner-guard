<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\Pages\ManageScannerGuardAgentRules;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\ScannerGuardAgentRuleResource;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentRule;
use Livewire\Livewire;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgentRule::class)) {
        $this->markTestSkipped('The installed core has no agent rules (2.5+).');
    }
});

it('hides the agent rules resource unless the app serves agents', function () {
    expect(ScannerGuardAgentRuleResource::canAccess())->toBeFalse();
});

it('sets a rule for every agent from the panel', function () {
    config()->set('scanner-guard.http.server.enabled', true);
    ScannerGuardAgent::create(['source' => 'web-1', 'rules' => ['web_attack', 'ssh_bruteforce'], 'last_seen_at' => now()]);

    expect(ScannerGuardAgentRuleResource::canAccess())->toBeTrue()
        ->and(ScannerGuardAgentRuleResource::knownRuleNames())->toBe(['ssh_bruteforce', 'web_attack']);

    Livewire::test(ManageScannerGuardAgentRules::class)
        ->callAction('create', ['name' => 'web_attack', 'threshold' => 5, 'window' => '10m', 'ban' => '1d,7d , 30d'])
        ->assertHasNoActionErrors();

    expect(ScannerGuardAgentRule::query()->sole()->ban)->toBe('1d, 7d, 30d')
        ->and(ScannerGuard::agentRules())->toBe(['web_attack' => ['threshold' => 5, 'window' => 600, 'ban' => [86400, 604800, 2592000]]]);
});

it('refuses values the agents would not take', function (array $data, string $field) {
    config()->set('scanner-guard.http.server.enabled', true);

    Livewire::test(ManageScannerGuardAgentRules::class)
        ->callAction('create', $data + ['name' => 'web_attack', 'threshold' => 5, 'window' => '10m', 'ban' => '1d'])
        ->assertHasActionErrors([$field]);
})->with([
    'threshold 0' => [['threshold' => 0], 'threshold'],
    'window words' => [['window' => '10 minutes'], 'window'],
    'window too long' => [['window' => '31d'], 'window'],
    'ban step too long' => [['ban' => '1d, 400d'], 'ban'],
    'ban empty step' => [['ban' => '1d,,7d'], 'ban'],
    'name too long' => [['name' => str_repeat('x', 31)], 'name'],
]);
