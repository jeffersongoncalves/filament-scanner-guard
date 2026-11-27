<?php

use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnosisResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnosisResource\Pages\ListScannerGuardAgentDiagnoses;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRuleResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRuleResource\Pages\ManageScannerGuardAgentNginxRules;
use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentDiagnosis;
use Livewire\Livewire;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgentDiagnosis::class)) {
        $this->markTestSkipped('The installed core has no nginx rules or diagnoses (2.8+).');
    }
});

it('hides both resources unless the app serves agents', function () {
    expect(ScannerGuardAgentNginxRuleResource::canAccess())->toBeFalse()
        ->and(ScannerGuardAgentDiagnosisResource::canAccess())->toBeFalse();
});

it('turns an optional nginx rule on for every server but the exceptions', function () {
    config()->set('scanner-guard.http.server.enabled', true);
    ScannerGuardAgent::create(['source' => 'web-1', 'last_seen_at' => now()]);
    ScannerGuardAgent::create(['source' => 'wp-1', 'last_seen_at' => now()]);

    expect(ScannerGuardAgentNginxRuleResource::canAccess())->toBeTrue()
        ->and(ScannerGuardAgentNginxRuleResource::describe('cms'))->toContain('WordPress');

    Livewire::test(ManageScannerGuardAgentNginxRules::class)
        ->callAction('create', ['name' => 'cms', 'enabled' => true, 'except' => ['wp-1']])
        ->assertHasNoActionErrors();

    expect(ScannerGuard::denylist('web-1')['nginx_rules'])->toBe(['cms'])
        ->and(ScannerGuard::denylist('wp-1')['nginx_rules'])->toBe([])
        ->and(ScannerGuardAgentNginxRuleResource::nameOptions(null))->not->toHaveKey('cms');
});

it('refuses a rule the agent does not know', function () {
    config()->set('scanner-guard.http.server.enabled', true);

    Livewire::test(ManageScannerGuardAgentNginxRules::class)
        ->callAction('create', ['name' => 'anything', 'enabled' => true])
        ->assertHasActionErrors(['name']);
});

it('asks the chosen servers for a diagnosis and shows the verdict', function () {
    config()->set('scanner-guard.http.server.enabled', true);
    ScannerGuardAgent::create(['source' => 'web-1', 'last_seen_at' => now()]);
    ScannerGuardAgent::create(['source' => 'web-2', 'last_seen_at' => now()]);

    Livewire::test(ListScannerGuardAgentDiagnoses::class)
        ->callAction('diagnoseIp', ['ip' => '203.0.113.9', 'sources' => ['web-1', 'web-2'], 'capture' => 30])
        ->assertHasNoActionErrors();

    expect(ScannerGuardAgentDiagnosis::query()->pluck('source')->sort()->values()->all())->toBe(['web-1', 'web-2']);

    $diagnosis = ScannerGuardAgentDiagnosis::query()->where('source', 'web-1')->sole();
    ScannerGuard::recordDiagnosis('web-1', $diagnosis->id, "== verdict ==\n  BLOCKED on this server by: fail2ban\n<script>");

    expect(ScannerGuardAgentDiagnosisResource::verdict($diagnosis->fresh()))->toBe('BLOCKED on this server by: fail2ban');

    Livewire::test(ListScannerGuardAgentDiagnoses::class)
        ->assertCanSeeTableRecords(ScannerGuardAgentDiagnosis::all())
        ->assertSee('BLOCKED on this server by: fail2ban')
        ->assertDontSee('<script>', escape: false);
});

it('refuses an invalid IP or capture', function (array $data, string $field) {
    config()->set('scanner-guard.http.server.enabled', true);
    ScannerGuardAgent::create(['source' => 'web-1', 'last_seen_at' => now()]);

    Livewire::test(ListScannerGuardAgentDiagnoses::class)
        ->callAction('diagnoseIp', $data + ['ip' => '203.0.113.9', 'sources' => ['web-1'], 'capture' => 0])
        ->assertHasActionErrors([$field]);
})->with([
    'not an IP' => [['ip' => 'example.com'], 'ip'],
    'capture too long' => [['capture' => 121], 'capture'],
    'no server' => [['sources' => []], 'sources'],
]);
