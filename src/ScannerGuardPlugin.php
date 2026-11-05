<?php

namespace JeffersonGoncalves\Filament\ScannerGuard;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use JeffersonGoncalves\Filament\ScannerGuard\Pages\MetricsPage;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\ScannerGuardBanResource;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBlocklist\ScannerGuardBlocklistResource;

class ScannerGuardPlugin implements Plugin
{
    protected ?string $navigationGroup = null;

    protected int $chartDays = 14;

    protected bool|Closure $groupRecordActions = false;

    protected RecordActionsPosition|Closure|null $recordActionsPosition = null;

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
            position: $this->recordActionsPosition,
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
}
