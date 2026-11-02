<?php

namespace JeffersonGoncalves\Filament\ScannerGuard\Support;

use Illuminate\Support\Facades\Log;
use JeffersonGoncalves\Filament\ScannerGuard\Models\ScannerGuardBlocklistEntry;
use JeffersonGoncalves\ScannerGuard\Jobs\ExportDenylist;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;
use JeffersonGoncalves\ScannerGuard\ScannerGuard;
use Throwable;

/**
 * Glue between the panel and the core package: merges the panel-managed
 * blocklist into the core config and keeps the nginx denylist in step.
 */
class Denylist
{
    public const CACHE_KEY = 'filament-scanner-guard:blocklist';

    /**
     * Config lists as loaded from config/scanner-guard.php, before any panel entry was merged in.
     *
     * @var array{ip: list<string>, user_agent: list<string>}|null
     */
    protected static ?array $base = null;

    /**
     * The core reads both lists from config on every check, so merging here
     * covers the middleware and the export alike. Config entries always stay.
     */
    public static function apply(): void
    {
        static::$base ??= [
            ScannerGuardBlocklistEntry::TYPE_IP => array_values(array_map('strval', (array) config('scanner-guard.ip_blocklist', []))),
            ScannerGuardBlocklistEntry::TYPE_USER_AGENT => array_values(array_map('strval', (array) config('scanner-guard.user_agent_blocklist.patterns', []))),
        ];

        /** @var array<string, list<string>> $entries */
        $entries = app(ScannerGuard::class)->cache()->rememberForever(
            self::CACHE_KEY,
            fn (): array => ScannerGuardBlocklistEntry::query()
                ->orderBy('id')
                ->get(['type', 'value'])
                ->groupBy('type')
                ->map(fn ($group): array => $group->pluck('value')->all())
                ->all(),
        );

        config([
            'scanner-guard.ip_blocklist' => static::merge(ScannerGuardBlocklistEntry::TYPE_IP, $entries),
            'scanner-guard.user_agent_blocklist.patterns' => static::merge(ScannerGuardBlocklistEntry::TYPE_USER_AGENT, $entries),
        ]);
    }

    /**
     * After a blocklist change: drop the cached list, re-merge, re-export.
     */
    public static function refresh(): void
    {
        app(ScannerGuard::class)->cache()->forget(self::CACHE_KEY);

        static::apply();
        static::export();
    }

    /**
     * Lifts a ban early. Deleting the row alone isn't enough: the core's
     * cache fast path would keep answering "banned" until the old expiry.
     */
    public static function lift(ScannerGuardBan $ban): void
    {
        $ban->delete();

        // ponytail: mirrors the core's protected banKey(); move to a core unban() if it ever changes.
        app(ScannerGuard::class)->cache()->forget("scanner-guard:ban:{$ban->ip_hash}");

        static::export();
    }

    /**
     * Same job the core queues on every ban (unique, so a burst collapses into one export).
     */
    public static function export(): void
    {
        if (! config('scanner-guard.sync_to_nginx', false)) {
            return;
        }

        // Like the core's on-ban export: a queue failure is logged, never undoes or breaks the change.
        try {
            ExportDenylist::dispatch();
        } catch (Throwable $e) {
            Log::error('filament-scanner-guard: failed to queue nginx export', ['exception' => $e->getMessage()]);
        }
    }

    /**
     * An IPv4/IPv6 address, optionally with a /prefix in range — what nginx accepts in `deny`.
     */
    public static function isValidRange(string $entry): bool
    {
        [$address, $prefix] = array_pad(explode('/', $entry, 2), 2, null);

        if (filter_var($address, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return $prefix === null
            || (ctype_digit($prefix) && (int) $prefix <= (str_contains((string) $address, ':') ? 128 : 32));
    }

    /**
     * @param  array<string, list<string>>  $entries
     * @return list<string>
     */
    protected static function merge(string $type, array $entries): array
    {
        return array_values(array_unique([...(static::$base[$type] ?? []), ...($entries[$type] ?? [])]));
    }
}
