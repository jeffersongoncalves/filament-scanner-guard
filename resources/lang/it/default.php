<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Ban di Scanner Guard',
        'plural_label' => 'Ban di Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Metriche',
    ],
    'stats' => [
        'total_bans' => 'Ban totali',
        'active_bans' => 'Ban attivi',
        'expired_bans' => 'Ban scaduti',
        'total_hits' => 'Rilevazioni totali',
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valore corrispondente',
        'hit_count' => 'Rilevazioni',
        'banned_at' => 'Bannato il',
        'expires_at' => 'Scade il',
        'is_active' => 'Attivo',
    ],
    'reason' => [
        'scanner_path' => 'Percorso scanner',
        'asn_blocklist' => 'Lista di blocco ASN',
    ],
    'filter' => [
        'label' => 'Stato',
        'placeholder' => 'Tutti',
        'true_label' => 'Attivo',
        'false_label' => 'Scaduto',
    ],
    'unban' => [
        'label' => 'Sbanna',
        'bulk_label' => 'Sbanna selezionati',
        'success' => 'Sbannato',
    ],
];
