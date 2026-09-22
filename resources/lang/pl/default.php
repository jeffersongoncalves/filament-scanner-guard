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
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Powód',
        'matched_value' => 'Dopasowana wartość',
        'hit_count' => 'Trafienia',
        'banned_at' => 'Zablokowano',
        'expires_at' => 'Wygasa',
        'is_active' => 'Aktywna',
    ],
    'reason' => [
        'scanner_path' => 'Ścieżka skanera',
        'asn_blocklist' => 'Czarna lista ASN',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Wszystkie',
        'true_label' => 'Aktywne',
        'false_label' => 'Wygasłe',
    ],
    'unban' => [
        'label' => 'Odblokuj',
        'bulk_label' => 'Odblokuj zaznaczone',
        'success' => 'Odblokowano',
    ],
    'charts' => [
        'bans_per_day' => 'Blokady dziennie (ostatnie 14 dni)',
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
