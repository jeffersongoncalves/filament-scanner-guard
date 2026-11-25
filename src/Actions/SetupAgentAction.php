<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use JeffersonGoncalves\Filament\ScannerGuard\ScannerGuardPlugin;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;
use Livewire\Component;

/**
 * Steps to set up a scanner-guard-agent and, when the plugin allows it, its
 * secret. The secret only ever travels in a browser event: never in the page,
 * in the Livewire state or in the log.
 */
class SetupAgentAction extends Action
{
    public const SECRET_EVENT = 'scanner-guard-agent-secret';

    protected const PASSWORD_ATTEMPTS = 5;

    public static function getDefaultName(): ?string
    {
        return 'setupAgent';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-scanner-guard::default.agents.setup.label'))
            ->icon('heroicon-o-command-line')
            ->modalWidth('3xl')
            ->modalContent(fn (): View => view('filament-scanner-guard::agents.setup', ['steps' => static::setupSteps()]))
            ->form(fn (): array => static::canReveal() ? $this->secretFields() : [])
            ->modalSubmitAction(fn (StaticAction $action): StaticAction|bool => static::canReveal() ? $action : false)
            ->modalSubmitActionLabel(__('filament-scanner-guard::default.agents.setup.reveal'))
            ->action(function (array $data, Component $livewire, ?Form $form): void {
                abort_unless(static::canReveal(), 403);

                $source = filled($data['source'] ?? null) ? (string) $data['source'] : null;
                $secret = app(ScannerGuard::class)->secretFor($source);

                // Not left behind in the Livewire state of the open modal.
                $form?->fill(['source' => $source]);

                if ($secret === '') {
                    Notification::make()->danger()->title(__('filament-scanner-guard::default.agents.setup.missing'))->send();
                } else {
                    Log::info('filament-scanner-guard: agent secret shown', ['user' => filament()->auth()->id(), 'source' => $source]);
                    $livewire->dispatch(self::SECRET_EVENT, secret: $secret);
                }

                $this->halt(); // keep the modal open
            });
    }

    protected static function canReveal(): bool
    {
        return ScannerGuardPlugin::get()->canRevealAgentSecret();
    }

    /**
     * @return array<Select|TextInput>
     */
    protected function secretFields(): array
    {
        $sources = array_map('strval', array_keys((array) config('scanner-guard.http.secrets', [])));

        return [
            Select::make('source')
                ->label(__('filament-scanner-guard::default.agents.setup.secret_of'))
                ->placeholder(__('filament-scanner-guard::default.agents.setup.shared'))
                ->options(array_combine($sources, $sources))
                ->in($sources)
                ->visible($sources !== []),
            TextInput::make('password')
                ->label(__('filament-scanner-guard::default.agents.setup.password'))
                ->password()
                ->autocomplete('current-password')
                ->required()
                ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                    static::checkPassword((string) $value, $fail);
                }),
        ];
    }

    protected static function checkPassword(string $password, Closure $fail): void
    {
        $user = filament()->auth()->user();
        $key = 'filament-scanner-guard:agent-secret:'.filament()->auth()->id();

        if (RateLimiter::tooManyAttempts($key, self::PASSWORD_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            $fail(__('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]));

            return;
        }

        $hash = (string) $user?->getAuthPassword();

        // Empty: e.g. an account that only signs in through a social provider.
        if ($hash === '' || ! Hash::check($password, $hash)) {
            RateLimiter::hit($key);
            $fail(__('auth.password'));

            return;
        }

        RateLimiter::clear($key);
    }

    /**
     * @return list<array{label: string, code: string, secret: bool}>
     */
    public static function setupSteps(): array
    {
        $url = url((string) config('scanner-guard.http.server.path', 'scanner-guard/bans'));
        $ip = (string) request()->ip();

        return array_map(
            fn (array $step): array => ['label' => __('filament-scanner-guard::default.agents.setup.'.$step[0]), 'code' => $step[1], 'secret' => $step[0] === 'secret'],
            [
                ['install', 'curl -fsSL https://raw.githubusercontent.com/jeffersongoncalves/scanner-guard-agent/main/install.sh | sudo sh'],
                ['config', "source: vps-01\ncentral:\n  url: {$url}\n  secret_file: /etc/scanner-guard/secret\nignore: [{$ip}]"],
                ['secret', "sudo sh -c 'umask 077; cat > /etc/scanner-guard/secret'"],
                ['test', 'journalctl -u ssh -u sshd --since -1d -o cat | sudo scanner-guard-agent test -'],
                ['start', "sudo systemctl enable --now scanner-guard-agent\njournalctl -u scanner-guard-agent -f"],
            ],
        );
    }
}
