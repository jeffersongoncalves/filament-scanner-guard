<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Scanner Guard Ban',
        'plural_label' => 'Scanner Guard Bans',
    ],
    'metrics' => [
        'title' => 'Metrics',
    ],
    'stats' => [
        'total_bans' => 'Total Bans',
        'active_bans' => 'Active Bans',
        'expired_bans' => 'Expired Bans',
        'total_hits' => 'Total Hits',
    ],
    'table' => [
        'ip_hash' => 'IP Hash',
        'reason' => 'Reason',
        'matched_value' => 'Matched Value',
        'hit_count' => 'Hits',
        'banned_at' => 'Banned At',
        'expires_at' => 'Expires At',
        'is_active' => 'Active',
    ],
    'reason' => [
        'scanner_path' => 'Scanner Path',
        'asn_blocklist' => 'ASN Blocklist',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'All',
        'true_label' => 'Active',
        'false_label' => 'Expired',
    ],
    'unban' => [
        'label' => 'Unban',
        'bulk_label' => 'Unban selected',
        'success' => 'Unbanned',
    ],
    'charts' => [
        'bans_per_day' => 'Bans per day (last 14 days)',
        'bans_by_reason' => 'Bans by reason',
        'top_matched_values' => 'Top matched values',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Ban details',
        ],
    ],
    'extend' => [
        'label' => 'Extend',
        'success' => 'Ban extended',
        'form' => [
            'duration' => 'Extend by',
        ],
        'options' => [
            'hour' => '1 hour',
            'day' => '1 day',
            'week' => '1 week',
            'month' => '1 month',
        ],
    ],
    'purge' => [
        'label' => 'Purge expired',
        'success' => 'Expired bans purged',
    ],
];
