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
        'recent_bans' => 'Заборони (останні :days днів)',
        'repeat_offenders' => 'Активні повторні порушники',
    ],
    'table' => [
        'ip_hash' => 'Хеш IP',
        'reason' => 'Причина',
        'matched_value' => 'Збігле значення',
        'hit_count' => 'Спрацьовування',
        'banned_at' => 'Заборонено',
        'expires_at' => 'Закінчується',
        'is_active' => 'Активно',
        'offense' => 'Порушення',
    ],
    'reason' => [
        'scanner_path' => 'Шлях сканера',
        'asn_blocklist' => 'Блок-лист ASN',
        'not_found_flood' => 'Флуд 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Вручну',
    ],
    'ban_ip' => [
        'label' => 'Забанити IP',
        'success' => 'IP забанено',
        'form' => [
            'ip' => 'IP-адреса',
        ],
    ],
    'blocklist' => [
        'label' => 'Запис чорного списку',
        'plural_label' => 'Чорний список',
        'type' => 'Тип',
        'value' => 'Значення',
        'note' => 'Примітка',
        'created_at' => 'Додано',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Шаблон User-Agent',
        ],
        'help' => [
            'ip' => 'Адреса IPv4/IPv6 або діапазон CIDR, наприклад 185.177.72.0/24. Блокується назавжди.',
            'user_agent' => 'Шаблон fnmatch без урахування регістру, наприклад *zgrab*. Порівнюється з повним User-Agent.',
        ],
        'invalid_ip' => 'Введіть коректну IP-адресу або діапазон CIDR.',
    ],
    'filter' => [
        'label' => 'Статус',
        'placeholder' => 'Усі',
        'true_label' => 'Активні',
        'false_label' => 'Завершені',
        'repeat_offenders' => 'Повторні порушники',
    ],
    'unban' => [
        'label' => 'Розбанити',
        'bulk_label' => 'Розбанити вибрані',
        'success' => 'Розбанено',
    ],
    'charts' => [
        'bans_per_day' => 'Заборони за днями (останні :days днів)',
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
