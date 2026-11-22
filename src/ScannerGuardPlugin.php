<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardAgentResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklistResource;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;

class ScannerGuardPlugin implements Plugin
{
    protected ?string $navigationGroup = null;

    protected int $chartDays = 14;

    protected bool|Closure $groupRecordActions = false;

    protected ActionsPosition|Closure|null $recordActionsPosition = null;

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
    public function recordActionsPosition(ActionsPosition|Closure|null $position): static
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
        return $table->actions(
            $table->evaluate($this->groupRecordActions) ? ActionGroup::make($actions) : $actions,
            // Resolved here: a closure returning null must keep the table/global position.
            position: $table->evaluate($this->recordActionsPosition),
        );
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
