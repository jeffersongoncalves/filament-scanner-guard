<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Pages;

use Filament\Pages\SimplePage;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Filament\ScannerGuard\Resources\ScannerGuardBans\Tables\ScannerGuardBansTable;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgent;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardAgentMetric;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

/**
 * Served without login when ScannerGuardPlugin::publicStatusPage() is on:
 * counts only, never an IP, IP hash, user, key, event field or log line.
 */
class PublicStatusPage extends SimplePage
{
    protected string $view = 'filament-scanner-guard::pages.status';

    protected Width|string|null $maxContentWidth = Width::FiveExtraLarge;

    public function getTitle(): string
    {
        return __('filament-scanner-guard::default.status.title');
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        // ponytail: one shared entry for every visitor, so an anonymous page can't hammer the DB.
        return Cache::remember('filament-scanner-guard.status', 60, fn (): array => static::status());
    }

    /**
     * @return array{servers: list<array<string, mixed>>, online: int, blocked: int, attacks: int, bans: int, bansByReason: array<string, int>}
     */
    public static function status(): array
    {
        $since = now()->startOfHour()->subHours(23);
        $hours = collect(range(0, 23))->map(fn (int $i): string => $since->copy()->addHours($i)->format('Y-m-d H'));

        // Each section degrades on its own: an older core has no metrics (2.6) or agents (2.1) table.
        $metrics = static::tableExists(ScannerGuardAgentMetric::class)
            ? ScannerGuardAgentMetric::query()->where('hour', '>=', $since)->get()
            : collect();

        $servers = static::tableExists(ScannerGuardAgent::class)
            ? ScannerGuardAgent::all()->sortBy('source')->map(function (ScannerGuardAgent $agent) use ($metrics, $hours): array {
                $own = $metrics->where('source', $agent->source);
                $perHour = $own->groupBy(fn (ScannerGuardAgentMetric $metric): string => $metric->hour->format('Y-m-d H'))->map->sum('matches');

                return [
                    'source' => $agent->source,
                    'online' => $agent->isOnline(),
                    'version' => $agent->version,
                    'last_seen' => $agent->last_seen_at->diffForHumans(),
                    'active_bans' => $agent->active_bans,
                    'attacks' => (int) $own->sum('matches'),
                    'hourly' => $hours->map(fn (string $hour): int => (int) ($perHour[$hour] ?? 0))->all(),
                    'top_rules' => $own->groupBy('rule')->map->sum('matches')->sortDesc()->take(3)->all(),
                ];
            })->values()->all()
            : [];

        $bansByReason = static::tableExists(ScannerGuardBan::class)
            ? ScannerGuardBan::query()
                ->toBase()
                ->where('banned_at', '>=', now()->subDay())
                ->selectRaw('reason, count(*) as total')
                ->groupBy('reason')
                ->orderByDesc('total')
                ->pluck('total', 'reason')
                ->mapWithKeys(fn (mixed $total, mixed $reason): array => [ScannerGuardBansTable::reasonLabel((string) $reason) => (int) $total])
                ->all()
            : [];

        return [
            'servers' => $servers,
            'online' => count(array_filter(array_column($servers, 'online'))),
            // Every agent pulls the same central list: the largest count is what is blocked now.
            'blocked' => (int) max([0, ...array_column($servers, 'active_bans')]),
            'attacks' => (int) $metrics->sum('matches'),
            'bans' => array_sum($bansByReason),
            'bansByReason' => $bansByReason,
        ];
    }

    /**
     * @param  class-string<Model>  $model
     */
    protected static function tableExists(string $model): bool
    {
        return class_exists($model) && rescue(fn (): bool => Schema::hasTable((new $model)->getTable()), false, false);
    }
}
