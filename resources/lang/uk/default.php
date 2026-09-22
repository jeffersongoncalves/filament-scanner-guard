<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Заборона Scanner Guard',
        'plural_label' => 'Заборони Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Метрики',
    ],
    'stats' => [
        'total_bans' => 'Усього заборон',
        'active_bans' => 'Активні заборони',
        'expired_bans' => 'Завершені заборони',
        'total_hits' => 'Усього спрацьовувань',
    ],
    'table' => [
        'ip_hash' => 'Хеш IP',
        'reason' => 'Причина',
        'matched_value' => 'Збігле значення',
        'hit_count' => 'Спрацьовування',
        'banned_at' => 'Заборонено',
        'expires_at' => 'Закінчується',
        'is_active' => 'Активно',
    ],
    'reason' => [
        'scanner_path' => 'Шлях сканера',
        'asn_blocklist' => 'Блок-лист ASN',
    ],
    'filter' => [
        'label' => 'Статус',
        'placeholder' => 'Усі',
        'true_label' => 'Активні',
        'false_label' => 'Завершені',
    ],
    'unban' => [
        'label' => 'Розбанити',
        'bulk_label' => 'Розбанити вибрані',
        'success' => 'Розбанено',
    ],
];
