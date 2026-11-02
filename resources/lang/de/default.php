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
        'recent_bans' => 'Sperren (letzte :days Tage)',
        'repeat_offenders' => 'Aktive Wiederholungstäter',
    ],
    'table' => [
        'ip_hash' => 'IP-Hash',
        'reason' => 'Grund',
        'matched_value' => 'Übereinstimmender Wert',
        'hit_count' => 'Treffer',
        'banned_at' => 'Gesperrt am',
        'expires_at' => 'Läuft ab am',
        'is_active' => 'Aktiv',
        'offense' => 'Verstoß',
    ],
    'reason' => [
        'scanner_path' => 'Scanner-Pfad',
        'asn_blocklist' => 'ASN-Sperrliste',
        'not_found_flood' => '404-Flut',
        'user_agent' => 'User-Agent',
        'manual' => 'Manuell',
    ],
    'ban_ip' => [
        'label' => 'IP sperren',
        'success' => 'IP gesperrt',
        'form' => [
            'ip' => 'IP-Adresse',
        ],
    ],
    'blocklist' => [
        'label' => 'Sperrlisteneintrag',
        'plural_label' => 'Sperrliste',
        'type' => 'Typ',
        'value' => 'Wert',
        'note' => 'Notiz',
        'created_at' => 'Hinzugefügt am',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent-Muster',
        ],
        'help' => [
            'ip' => 'Eine IPv4/IPv6-Adresse oder ein CIDR-Bereich, z. B. 185.177.72.0/24. Dauerhaft gesperrt.',
            'user_agent' => 'fnmatch-Muster, ohne Beachtung der Groß-/Kleinschreibung, z. B. *zgrab*. Wird mit dem vollständigen User-Agent verglichen.',
        ],
        'invalid_ip' => 'Geben Sie eine gültige IP-Adresse oder einen gültigen CIDR-Bereich ein.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Alle',
        'true_label' => 'Aktiv',
        'false_label' => 'Abgelaufen',
        'repeat_offenders' => 'Wiederholungstäter',
    ],
    'unban' => [
        'label' => 'Entsperren',
        'bulk_label' => 'Ausgewählte entsperren',
        'success' => 'Entsperrt',
    ],
    'charts' => [
        'bans_per_day' => 'Sperren pro Tag (letzte :days Tage)',
        'bans_by_reason' => 'Sperren nach Grund',
        'top_matched_values' => 'Top übereinstimmende Werte',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Sperrdetails',
        ],
    ],
    'extend' => [
        'label' => 'Verlängern',
        'success' => 'Sperre verlängert',
        'form' => [
            'duration' => 'Verlängern um',
        ],
        'options' => [
            'hour' => '1 Stunde',
            'day' => '1 Tag',
            'week' => '1 Woche',
            'month' => '1 Monat',
        ],
    ],
    'purge' => [
        'label' => 'Abgelaufene löschen',
        'success' => 'Abgelaufene Sperren gelöscht',
    ],
];
