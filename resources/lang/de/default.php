<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Scanner-Guard-Sperre',
        'plural_label' => 'Scanner-Guard-Sperren',
    ],
    'metrics' => [
        'title' => 'Metriken',
    ],
    'stats' => [
        'total_bans' => 'Sperren gesamt',
        'active_bans' => 'Aktive Sperren',
        'expired_bans' => 'Abgelaufene Sperren',
        'total_hits' => 'Treffer gesamt',
    ],
    'table' => [
        'ip_hash' => 'IP-Hash',
        'reason' => 'Grund',
        'matched_value' => 'Übereinstimmender Wert',
        'hit_count' => 'Treffer',
        'banned_at' => 'Gesperrt am',
        'expires_at' => 'Läuft ab am',
        'is_active' => 'Aktiv',
    ],
    'reason' => [
        'scanner_path' => 'Scanner-Pfad',
        'asn_blocklist' => 'ASN-Sperrliste',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Alle',
        'true_label' => 'Aktiv',
        'false_label' => 'Abgelaufen',
    ],
    'unban' => [
        'label' => 'Entsperren',
        'bulk_label' => 'Ausgewählte entsperren',
        'success' => 'Entsperrt',
    ],
];
