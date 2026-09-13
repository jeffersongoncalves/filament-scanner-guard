<?php

return [
    'resource' => [
        'label' => 'Scanner Guard Ban',
        'plural_label' => 'Scanner Guard Bans',
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
];
