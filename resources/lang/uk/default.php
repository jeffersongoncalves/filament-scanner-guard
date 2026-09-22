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
    'charts' => [
        'bans_per_day' => 'Заборони за днями (останні 14 днів)',
        'bans_by_reason' => 'Заборони за причиною',
        'top_matched_values' => 'Топ збіглих значень',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Деталі заборони',
        ],
    ],
    'extend' => [
        'label' => 'Продовжити',
        'success' => 'Заборону продовжено',
        'form' => [
            'duration' => 'Продовжити на',
        ],
        'options' => [
            'hour' => '1 годину',
            'day' => '1 день',
            'week' => '1 тиждень',
            'month' => '1 місяць',
        ],
    ],
    'purge' => [
        'label' => 'Видалити завершені',
        'success' => 'Завершені заборони видалено',
    ],
];
