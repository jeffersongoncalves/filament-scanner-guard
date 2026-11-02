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
        'recent_bans' => 'Bans (last :days days)',
        'repeat_offenders' => 'Active Repeat Offenders',
    ],
    'table' => [
        'ip_hash' => 'IP Hash',
        'reason' => 'Reason',
        'matched_value' => 'Matched Value',
        'hit_count' => 'Hits',
        'banned_at' => 'Banned At',
        'expires_at' => 'Expires At',
        'is_active' => 'Active',
        'offense' => 'Offense',
    ],
    'reason' => [
        'scanner_path' => 'Scanner Path',
        'asn_blocklist' => 'ASN Blocklist',
        'not_found_flood' => '404 Flood',
        'user_agent' => 'User-Agent',
        'manual' => 'Manual',
    ],
    'ban_ip' => [
        'label' => 'Ban IP',
        'success' => 'IP banned',
        'form' => [
            'ip' => 'IP address',
        ],
    ],
    'blocklist' => [
        'label' => 'Blocklist Entry',
        'plural_label' => 'Blocklist',
        'type' => 'Type',
        'value' => 'Value',
        'note' => 'Note',
        'created_at' => 'Added At',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent pattern',
        ],
        'help' => [
            'ip' => 'An IPv4/IPv6 address or CIDR range, e.g. 185.177.72.0/24. Blocked permanently.',
            'user_agent' => 'fnmatch pattern, case-insensitive, e.g. *zgrab*. Matched against the full User-Agent.',
        ],
        'invalid_ip' => 'Enter a valid IP address or CIDR range.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'All',
        'true_label' => 'Active',
        'false_label' => 'Expired',
        'repeat_offenders' => 'Repeat offenders',
    ],
    'unban' => [
        'label' => 'Unban',
        'bulk_label' => 'Unban selected',
        'success' => 'Unbanned',
    ],
    'charts' => [
        'bans_per_day' => 'Bans per day (last :days days)',
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
