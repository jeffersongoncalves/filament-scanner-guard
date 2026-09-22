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
        'title' => 'Statistieken',
    ],
    'stats' => [
        'total_bans' => 'Totaal aantal bans',
        'active_bans' => 'Actieve bans',
        'expired_bans' => 'Verlopen bans',
        'total_hits' => 'Totaal aantal hits',
    ],
    'table' => [
        'ip_hash' => 'IP-hash',
        'reason' => 'Reden',
        'matched_value' => 'Overeenkomende waarde',
        'hit_count' => 'Hits',
        'banned_at' => 'Verbannen op',
        'expires_at' => 'Verloopt op',
        'is_active' => 'Actief',
    ],
    'reason' => [
        'scanner_path' => 'Scannerpad',
        'asn_blocklist' => 'ASN-blokkadelĳst',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Alle',
        'true_label' => 'Actief',
        'false_label' => 'Verlopen',
    ],
    'unban' => [
        'label' => 'Deblokkeer',
        'bulk_label' => 'Selectie deblokkeren',
        'success' => 'Gedeblokkeerd',
    ],
    'charts' => [
        'bans_per_day' => 'Bans per dag (laatste 14 dagen)',
        'bans_by_reason' => 'Bans per reden',
        'top_matched_values' => 'Top overeenkomende waarden',
    ],
];
