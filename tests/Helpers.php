<?php

use JeffersonGoncalves\ScannerGuard\Facades\ScannerGuard;
use JeffersonGoncalves\ScannerGuard\Models\ScannerGuardBan;

/**
 * Mirrors ScannerGuard::ban(): stores the row and bumps its day's stats.
 * Pass $countStats = false to simulate a ban recorded before ban-time counting.
 */
function createBan(array $overrides = [], bool $countStats = true): ScannerGuardBan
{
    $ban = ScannerGuardBan::create(array_merge([
        'ip_hash' => hash('sha256', uniqid()),
        'reason' => ScannerGuardBan::REASON_SCANNER_PATH,
        'matched_value' => 'wp-admin/setup-config.php',
        'hit_count' => 3,
        'banned_at' => now(),
        'expires_at' => now()->addDay(),
    ], $overrides));

    if ($countStats) {
        ScannerGuard::mergeDailyStats($ban->banned_at->toDateString(), [
            'bans_count' => 1,
            'hits_total' => $ban->hit_count,
            'reason_stats' => [$ban->reason => 1],
            'top_matched_values' => [$ban->matched_value => $ban->hit_count],
        ], fn (int $a, int $b): int => $a + $b);
    }

    return $ban;
}
