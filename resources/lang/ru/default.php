<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Бан Scanner Guard',
        'plural_label' => 'Баны Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Метрики',
    ],
    'stats' => [
        'total_bans' => 'Всего банов',
        'active_bans' => 'Активные баны',
        'expired_bans' => 'Истёкшие баны',
        'total_hits' => 'Всего срабатываний',
    ],
    'table' => [
        'ip_hash' => 'Хеш IP',
        'reason' => 'Причина',
        'matched_value' => 'Совпавшее значение',
        'hit_count' => 'Срабатывания',
        'banned_at' => 'Забанен',
        'expires_at' => 'Истекает',
        'is_active' => 'Активен',
    ],
    'reason' => [
        'scanner_path' => 'Путь сканера',
        'asn_blocklist' => 'Блок-лист ASN',
    ],
    'filter' => [
        'label' => 'Статус',
        'placeholder' => 'Все',
        'true_label' => 'Активные',
        'false_label' => 'Истёкшие',
    ],
    'unban' => [
        'label' => 'Разбанить',
        'bulk_label' => 'Разбанить выбранные',
        'success' => 'Разбанен',
    ],
    'charts' => [
        'bans_per_day' => 'Баны по дням (последние 14 дней)',
        'bans_by_reason' => 'Баны по причине',
        'top_matched_values' => 'Топ совпавших значений',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Детали бана',
        ],
    ],
    'extend' => [
        'label' => 'Продлить',
        'success' => 'Бан продлён',
        'form' => [
            'duration' => 'Продлить на',
        ],
        'options' => [
            'hour' => '1 час',
            'day' => '1 день',
            'week' => '1 неделю',
            'month' => '1 месяц',
        ],
    ],
    'purge' => [
        'label' => 'Удалить истёкшие',
        'success' => 'Истёкшие баны удалены',
    ],
    'export' => [
        'completed' => 'Экспорт банов завершён, экспортировано строк: :count.',
        'failed' => 'Не удалось экспортировать строк: :count.',
    ],
];
