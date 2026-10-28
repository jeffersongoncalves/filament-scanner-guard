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
        'recent_bans' => 'Bans (laatste :days dagen)',
        'repeat_offenders' => 'Actieve herhaalde overtreders',
    ],
    'table' => [
        'ip_hash' => 'IP-hash',
        'reason' => 'Reden',
        'matched_value' => 'Overeenkomende waarde',
        'hit_count' => 'Hits',
        'banned_at' => 'Verbannen op',
        'expires_at' => 'Verloopt op',
        'is_active' => 'Actief',
        'offense' => 'Overtreding',
    ],
    'reason' => [
        'scanner_path' => 'Scannerpad',
        'asn_blocklist' => 'ASN-blokkadelĳst',
        'not_found_flood' => '404-vloed',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Alle',
        'true_label' => 'Actief',
        'false_label' => 'Verlopen',
        'repeat_offenders' => 'Herhaalde overtreders',
    ],
    'unban' => [
        'label' => 'Deblokkeer',
        'bulk_label' => 'Selectie deblokkeren',
        'success' => 'Gedeblokkeerd',
    ],
    'charts' => [
        'bans_per_day' => 'Bans per dag (laatste :days dagen)',
        'bans_by_reason' => 'Bans per reden',
        'top_matched_values' => 'Top overeenkomende waarden',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Ban-details',
        ],
    ],
    'extend' => [
        'label' => 'Verlengen',
        'success' => 'Ban verlengd',
        'form' => [
            'duration' => 'Verlengen met',
        ],
        'options' => [
            'hour' => '1 uur',
            'day' => '1 dag',
            'week' => '1 week',
            'month' => '1 maand',
        ],
    ],
    'purge' => [
        'label' => 'Verlopen opschonen',
        'success' => 'Verlopen bans opgeschoond',
    ],
];
