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
        'recent_bans' => 'Ban (ultimi :days giorni)',
        'repeat_offenders' => 'Recidivi attivi',
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valore corrispondente',
        'hit_count' => 'Rilevazioni',
        'banned_at' => 'Bannato il',
        'expires_at' => 'Scade il',
        'is_active' => 'Attivo',
        'offense' => 'Recidiva',
    ],
    'reason' => [
        'scanner_path' => 'Percorso scanner',
        'asn_blocklist' => 'Lista di blocco ASN',
        'not_found_flood' => 'Flood di 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Manuale',
    ],
    'ban_ip' => [
        'label' => 'Banna IP',
        'success' => 'IP bannato',
        'form' => [
            'ip' => 'Indirizzo IP',
        ],
    ],
    'blocklist' => [
        'label' => 'Voce della blocklist',
        'plural_label' => 'Blocklist',
        'type' => 'Tipo',
        'value' => 'Valore',
        'note' => 'Nota',
        'created_at' => 'Aggiunto il',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Pattern User-Agent',
        ],
        'help' => [
            'ip' => 'Un indirizzo IPv4/IPv6 o un intervallo CIDR, es. 185.177.72.0/24. Bloccato in modo permanente.',
            'user_agent' => 'Pattern fnmatch, senza distinzione tra maiuscole e minuscole, es. *zgrab*. Confrontato con lo User-Agent completo.',
        ],
        'invalid_ip' => 'Inserisci un indirizzo IP o un intervallo CIDR valido.',
    ],
    'filter' => [
        'label' => 'Stato',
        'placeholder' => 'Tutti',
        'true_label' => 'Attivo',
        'false_label' => 'Scaduto',
        'repeat_offenders' => 'Recidivi',
    ],
    'unban' => [
        'label' => 'Sbanna',
        'bulk_label' => 'Sbanna selezionati',
        'success' => 'Sbannato',
    ],
    'charts' => [
        'bans_per_day' => 'Ban al giorno (ultimi :days giorni)',
        'bans_by_reason' => 'Ban per motivo',
        'top_matched_values' => 'Principali valori corrispondenti',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Dettagli del ban',
        ],
    ],
    'extend' => [
        'label' => 'Estendi',
        'success' => 'Ban esteso',
        'form' => [
            'duration' => 'Estendi di',
        ],
        'options' => [
            'hour' => '1 ora',
            'day' => '1 giorno',
            'week' => '1 settimana',
            'month' => '1 mese',
        ],
    ],
    'purge' => [
        'label' => 'Elimina scaduti',
        'success' => 'Ban scaduti eliminati',
    ],
];
