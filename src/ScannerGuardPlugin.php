<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Pages\PublicStatusPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentDiagnoses\ScannerGuardAgentDiagnosisResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentEvents\ScannerGuardAgentEventResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentNginxRules\ScannerGuardAgentNginxRuleResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentRules\ScannerGuardAgentRuleResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgents\ScannerGuardAgentResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklist\ScannerGuardBlocklistResource;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentDiagnosis;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentEvent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentNginxRule;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentRule;

class ScannerGuardPlugin implements Plugin
{
    protected ?string $navigationGroup = null;

    protected int $chartDays = 14;

    protected bool|Closure $groupRecordActions = false;

    protected RecordActionsPosition|Closure|null $recordActionsPosition = null;

    protected bool|Closure $revealAgentSecret = false;

    protected bool|Closure $purgeExpiredBans = true;

    protected bool|Closure $publicStatusPage = false;

    protected string $publicStatusPagePath = 'scanner-guard-status';

    public function getId(): string
    {
        return 'filament-scanner-guard';
    }

    public function register(Panel $panel): void
    {
        // The cache/http drivers keep no ban rows: nothing to list or chart.
        if (config('scanner-guard.driver', 'database') !== 'database') {
            return;
        }

        $panel
            ->resources([
                ScannerGuardBanResource::class,
                ScannerGuardBlocklistResource::class,
            ])
            ->pages([
                MetricsPage::class,
            ]);

        // Core 2.1+: the resource itself stays hidden unless this app receives heartbeats.
        if (class_exists(ScannerGuardAgent::class)) {
            $panel->resources([ScannerGuardAgentResource::class]);
        }

        // Core 2.5+, hidden the same way until its migration ran.
        if (class_exists(ScannerGuardAgentRule::class)) {
            $panel->resources([ScannerGuardAgentRuleResource::class]);
        }

        // Core 2.6+, hidden the same way until its migration ran.
        if (class_exists(ScannerGuardAgentEvent::class)) {
            $panel->resources([ScannerGuardAgentEventResource::class]);
        }

        // Core 2.8+, hidden the same way until their migrations ran.
        if (class_exists(ScannerGuardAgentNginxRule::class)) {
            $panel->resources([ScannerGuardAgentNginxRuleResource::class]);
        }

        if (class_exists(ScannerGuardAgentDiagnosis::class)) {
            $panel->resources([ScannerGuardAgentDiagnosisResource::class]);
        }

        // Outside the auth middleware, like the login page.
        if (class_exists(ScannerGuardAgent::class) && value($this->publicStatusPage)) {
            $panel->routes(fn () => Route::get($this->publicStatusPagePath, PublicStatusPage::class)->name('scanner-guard-status'));
        }
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function navigationGroup(?string $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup ?? __('filament-scanner-guard::default.navigation.group');
    }

    public function chartDays(int $days): static
    {
        $this->chartDays = max($days, 1);

        return $this;
    }

    public function getChartDays(): int
    {
        return $this->chartDays;
    }

    public function groupRecordActions(bool|Closure $condition = true): static
    {
        $this->groupRecordActions = $condition;

        return $this;
    }

    /**
     * Null keeps the table's (or the global configureUsing) default.
     */
    public function recordActionsPosition(RecordActionsPosition|Closure|null $position): static
    {
        $this->recordActionsPosition = $position;

        return $this;
    }

    /**
     * Sets the plugin resources' record actions, grouped and positioned as configured.
     *
     * @param  array<Action|ActionGroup>  $actions
     */
    public function applyRecordActions(Table $table, array $actions): Table
    {
        return $table->recordActions(
            $table->evaluate($this->groupRecordActions) ? ActionGroup::make($actions) : $actions,
            // Resolved here: a closure returning null must keep the table/global position.
            position: $table->evaluate($this->recordActionsPosition),
        );
    }

    /**
     * Lets the panel show the agents' signing secret (after the user's
     * password). Off by default: whoever has it can forward bans as any
     * server sharing it, and sign denylist responses.
     */
    public function revealAgentSecret(bool|Closure $condition = true): static
    {
        $this->revealAgentSecret = $condition;

        return $this;
    }

    public function canRevealAgentSecret(): bool
    {
        return filament()->auth()->check() && (bool) value($this->revealAgentSecret);
    }

    /**
     * Shows the "Purge expired" action on the bans list. On by default; turn
     * it off to keep expired bans until the scheduled prune removes them.
     */
    public function purgeExpiredBans(bool|Closure $condition = true): static
    {
        $this->purgeExpiredBans = $condition;

        return $this;
    }

    public function canPurgeExpiredBans(): bool
    {
        return (bool) value($this->purgeExpiredBans);
    }

    /**
     * Serves a status page without login at {panel path}/{$path}: servers,
     * attacks and bans, never an IP, user or log line. Off by default.
     */
    public function publicStatusPage(bool|Closure $condition = true, string $path = 'scanner-guard-status'): static
    {
        $this->publicStatusPage = $condition;
        $this->publicStatusPagePath = $path;

        return $this;
    }

    /**
     * Ban duration in seconds per offense, from scanner-guard.escalation (empty = escalation off).
     *
     * @return list<int>
     */
    public static function escalation(): array
    {
        return array_values(array_map(intval(...), (array) config('scanner-guard.escalation', [])));
    }

    public static function escalationEnabled(): bool
    {
        return static::escalation() !== [];
    }

    /**
     * Central app of the http driver: rows carry the satellite's `source`
     * (core 2.0 add_source migration; null = banned locally). Off until that
     * migration ran, or filtering and sorting on the column would fail.
     */
    public static function receivesForwardedBans(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && once(fn (): bool => Schema::hasColumn(config('scanner-guard.table', 'scanner_guard_bans'), 'source'));
    }

    /**
     * Ban rows carry the IP and request path (core 2.2+ store_details). Off
     * until its add_details migration ran, or searching the path would fail.
     */
    public static function storesDetails(): bool
    {
        return (bool) config('scanner-guard.store_details', false)
            && once(fn (): bool => Schema::hasColumns(config('scanner-guard.table', 'scanner_guard_bans'), ['ip', 'path']));
    }

    /**
     * Ban rows carry the log lines a scanner-guard-agent banned on (core
     * add_evidence migration), stored like the other details.
     */
    public static function storesEvidence(): bool
    {
        return static::storesDetails()
            && once(fn (): bool => Schema::hasColumn(config('scanner-guard.table', 'scanner_guard_bans'), 'evidence'));
    }

    /**
     * Confirmation text of the actions deleting ban rows; null keeps Filament's default.
     */
    public static function deleteWarning(): ?string
    {
        return static::storesDetails() ? __('filament-scanner-guard::default.delete_warning') : null;
    }

    /**
     * Central app serving scanner-guard-agent rule settings (core 2.5+, after
     * its create_scanner_guard_agent_rules migration ran).
     */
    public static function servesAgentRules(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && class_exists(ScannerGuardAgentRule::class)
            && once(fn (): bool => Schema::hasTable((new ScannerGuardAgentRule)->getTable()));
    }

    /**
     * Central app storing scanner-guard-agent report events (core 2.6+, after
     * its create_scanner_guard_agent_events migration ran).
     */
    public static function receivesAgentEvents(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && class_exists(ScannerGuardAgentEvent::class)
            && once(fn (): bool => Schema::hasTable((new ScannerGuardAgentEvent)->getTable()));
    }

    /**
     * Central app serving the optional nginx rules to scanner-guard-agent
     * (core 2.8+, after its create_scanner_guard_agent_nginx_rules migration ran).
     */
    public static function servesAgentNginxRules(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && class_exists(ScannerGuardAgentNginxRule::class)
            && once(fn (): bool => Schema::hasTable((new ScannerGuardAgentNginxRule)->getTable()));
    }

    /**
     * Central app asking scanner-guard-agent for IP diagnoses (core 2.8+,
     * after its create_scanner_guard_agent_diagnostics migration ran).
     */
    public static function receivesAgentDiagnoses(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && class_exists(ScannerGuardAgentDiagnosis::class)
            && once(fn (): bool => Schema::hasTable((new ScannerGuardAgentDiagnosis)->getTable()));
    }

    /**
     * Central app recording scanner-guard-agent heartbeats (core 2.1+, after
     * its create_scanner_guard_agents migration ran).
     */
    public static function receivesHeartbeats(): bool
    {
        return (bool) config('scanner-guard.http.server.enabled', false)
            && class_exists(ScannerGuardAgent::class)
            && once(fn (): bool => Schema::hasTable((new ScannerGuardAgent)->getTable()));
    }
}
