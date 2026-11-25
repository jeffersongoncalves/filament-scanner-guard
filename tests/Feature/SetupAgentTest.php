<?php

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JeffersonGoncalves\Filament\ScannerGuard\Actions\SetupAgentAction;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource\Pages\ListScannerGuardAgents;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use Livewire\Livewire;

beforeEach(function () {
    if (! class_exists(ScannerGuardAgent::class)) {
        $this->markTestSkipped('The installed core has no agent heartbeats (2.1+).');
    }

    config([
        'scanner-guard.http.server.enabled' => true,
        'scanner-guard.http.secret' => 'SHARED-SECRET',
    ]);

    $user = (new User)->forceFill(['id' => 7, 'name' => 'Admin', 'password' => Hash::make('right')]);
    $this->actingAs($user);
});

function setupPage()
{
    return Livewire::test(ListScannerGuardAgents::class);
}

/**
 * The secret must reach the browser through the event only.
 */
function expectNoLeak($page, string $secret): void
{
    expect($page->html())->not->toContain($secret)
        ->and(json_encode($page->snapshot))->not->toContain($secret);
}

it('shows the setup steps with this app\'s url and the visitor\'s ip', function () {
    config(['scanner-guard.http.server.path' => 'guard/bans']);

    $codes = array_column(SetupAgentAction::setupSteps(), 'code');

    expect($codes)->toHaveCount(5)
        ->and($codes[1])->toContain('url: '.url('guard/bans'), 'ignore: ['.request()->ip().']');

    setupPage()->mountAction('setupAgent')
        ->assertSee('install.sh')
        ->assertSee(url('guard/bans'))
        ->assertSee('systemctl enable --now scanner-guard-agent');
});

it('keeps the secret out of reach by default', function () {
    $page = setupPage()->mountAction('setupAgent')
        ->assertDontSee(__('filament-scanner-guard::default.agents.setup.password'))
        ->assertDontSee('SHARED-SECRET');
    expectNoLeak($page, 'SHARED-SECRET');

    $page->call('callMountedAction')->assertForbidden();
});

it('asks the closure on every reveal', function () {
    $allowed = true;
    ScannerGuardPlugin::get()->revealAgentSecret(function () use (&$allowed): bool {
        return $allowed;
    });

    $page = setupPage()->mountAction('setupAgent')->assertSee(__('filament-scanner-guard::default.agents.setup.password'));
    $allowed = false;
    $page->call('callMountedAction')->assertForbidden();
});

it('reveals the shared secret after the password, and logs it without the value', function () {
    ScannerGuardPlugin::get()->revealAgentSecret();
    Log::spy();

    $page = setupPage()
        ->callAction('setupAgent', ['password' => 'right'])
        ->assertHasNoActionErrors()
        ->assertDispatched(SetupAgentAction::SECRET_EVENT, secret: 'SHARED-SECRET')
        ->assertActionHalted('setupAgent')
        ->assertDontSee('SHARED-SECRET');

    expectNoLeak($page, 'SHARED-SECRET');
    expect(json_encode($page->snapshot))->not->toContain('"right"');
    Log::shouldHaveReceived('info')->once()->with('filament-scanner-guard: agent secret shown', ['user' => 7, 'source' => null]);
});

it('reveals the secret of the chosen source', function () {
    ScannerGuardPlugin::get()->revealAgentSecret();
    config(['scanner-guard.http.secrets' => ['vps-01' => 'VPS-SECRET']]);

    setupPage()
        ->callAction('setupAgent', ['source' => 'vps-01', 'password' => 'right'])
        ->assertDispatched(SetupAgentAction::SECRET_EVENT, secret: 'VPS-SECRET');

    setupPage()
        ->callAction('setupAgent', ['source' => 'unknown', 'password' => 'right'])
        ->assertHasActionErrors(['source'])
        ->assertNotDispatched(SetupAgentAction::SECRET_EVENT);
});

it('refuses a wrong password, and any password after 5 attempts', function () {
    ScannerGuardPlugin::get()->revealAgentSecret();

    foreach (range(1, 5) as $attempt) {
        setupPage()->callAction('setupAgent', ['password' => 'wrong'])
            ->assertHasActionErrors(['password'])
            ->assertNotDispatched(SetupAgentAction::SECRET_EVENT);
    }

    setupPage()->callAction('setupAgent', ['password' => 'right'])
        ->assertHasActionErrors(['password'])
        ->assertNotDispatched(SetupAgentAction::SECRET_EVENT);

    $this->travel(61)->seconds();

    setupPage()->callAction('setupAgent', ['password' => 'right'])->assertDispatched(SetupAgentAction::SECRET_EVENT);
});

it('refuses an account without a password', function () {
    ScannerGuardPlugin::get()->revealAgentSecret();
    $this->actingAs((new User)->forceFill(['id' => 8, 'name' => 'Social', 'password' => null]));

    setupPage()->callAction('setupAgent', ['password' => ''])->assertHasActionErrors(['password']);
    setupPage()->callAction('setupAgent', ['password' => 'x'])
        ->assertHasActionErrors(['password'])
        ->assertNotDispatched(SetupAgentAction::SECRET_EVENT);
});

it('says so when no secret is configured', function () {
    ScannerGuardPlugin::get()->revealAgentSecret();
    config(['scanner-guard.http.secret' => null]);

    setupPage()->callAction('setupAgent', ['password' => 'right'])
        ->assertNotified(__('filament-scanner-guard::default.agents.setup.missing'))
        ->assertNotDispatched(SetupAgentAction::SECRET_EVENT);
});
