<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Blokada Scanner Guard',
        'plural_label' => 'Blokady Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Metryki',
    ],
    'stats' => [
        'total_bans' => 'Wszystkie blokady',
        'active_bans' => 'Aktywne blokady',
        'expired_bans' => 'Wygasłe blokady',
        'total_hits' => 'Wszystkie trafienia',
        'recent_bans' => 'Blokady (ostatnie :days dni)',
        'repeat_offenders' => 'Aktywni recydywiści',
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Powód',
        'matched_value' => 'Dopasowana wartość',
        'hit_count' => 'Trafienia',
        'banned_at' => 'Zablokowano',
        'expires_at' => 'Wygasa',
        'is_active' => 'Aktywna',
        'offense' => 'Wykroczenie',
    ],
    'reason' => [
        'scanner_path' => 'Ścieżka skanera',
        'asn_blocklist' => 'Czarna lista ASN',
        'not_found_flood' => 'Zalew 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Ręczna',
    ],
    'ban_ip' => [
        'label' => 'Zablokuj IP',
        'success' => 'IP zablokowany',
        'form' => [
            'ip' => 'Adres IP',
        ],
    ],
    'blocklist' => [
        'label' => 'Wpis listy blokad',
        'plural_label' => 'Lista blokad',
        'type' => 'Typ',
        'value' => 'Wartość',
        'note' => 'Notatka',
        'created_at' => 'Dodano',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Wzorzec User-Agent',
        ],
        'help' => [
            'ip' => 'Adres IPv4/IPv6 lub zakres CIDR, np. 185.177.72.0/24. Blokada stała.',
            'user_agent' => 'Wzorzec fnmatch, bez rozróżniania wielkości liter, np. *zgrab*. Porównywany z pełnym User-Agent.',
        ],
        'invalid_ip' => 'Podaj prawidłowy adres IP lub zakres CIDR.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Wszystkie',
        'true_label' => 'Aktywne',
        'false_label' => 'Wygasłe',
        'repeat_offenders' => 'Recydywiści',
    ],
    'unban' => [
        'label' => 'Odblokuj',
        'bulk_label' => 'Odblokuj zaznaczone',
        'success' => 'Odblokowano',
    ],
    'charts' => [
        'bans_per_day' => 'Blokady dziennie (ostatnie :days dni)',
        'bans_by_reason' => 'Blokady według powodu',
        'top_matched_values' => 'Najczęstsze dopasowane wartości',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Szczegóły blokady',
        ],
    ],
    'extend' => [
        'label' => 'Przedłuż',
        'success' => 'Blokada przedłużona',
        'form' => [
            'duration' => 'Przedłuż o',
        ],
        'options' => [
            'hour' => '1 godzinę',
            'day' => '1 dzień',
            'week' => '1 tydzień',
            'month' => '1 miesiąc',
        ],
    ],
    'purge' => [
        'label' => 'Usuń wygasłe',
        'success' => 'Wygasłe blokady usunięte',
    ],
];
