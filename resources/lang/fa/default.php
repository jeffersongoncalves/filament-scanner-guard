<?php

return [
    'navigation' => [
        'group' => 'محافظ اسکنر',
    ],
    'resource' => [
        'label' => 'مسدودیت محافظ اسکنر',
        'plural_label' => 'مسدودیت‌های محافظ اسکنر',
    ],
    'metrics' => [
        'title' => 'معیارها',
    ],
    'stats' => [
        'total_bans' => 'کل مسدودیت‌ها',
        'active_bans' => 'مسدودیت‌های فعال',
        'expired_bans' => 'مسدودیت‌های منقضی‌شده',
        'total_hits' => 'کل برخوردها',
    ],
    'table' => [
        'ip_hash' => 'هش IP',
        'reason' => 'دلیل',
        'matched_value' => 'مقدار منطبق',
        'hit_count' => 'برخوردها',
        'banned_at' => 'مسدود شده در',
        'expires_at' => 'منقضی می‌شود در',
        'is_active' => 'فعال',
    ],
    'reason' => [
        'scanner_path' => 'مسیر اسکنر',
        'asn_blocklist' => 'لیست سیاه ASN',
    ],
    'filter' => [
        'label' => 'وضعیت',
        'placeholder' => 'همه',
        'true_label' => 'فعال',
        'false_label' => 'منقضی‌شده',
    ],
    'unban' => [
        'label' => 'رفع مسدودیت',
        'bulk_label' => 'رفع مسدودیت موارد انتخاب‌شده',
        'success' => 'رفع مسدودیت شد',
    ],
    'charts' => [
        'bans_per_day' => 'مسدودیت روزانه (۱۴ روز اخیر)',
        'bans_by_reason' => 'مسدودیت بر اساس دلیل',
        'top_matched_values' => 'برترین مقادیر منطبق',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'جزئیات مسدودیت',
        ],
    ],
    'extend' => [
        'label' => 'تمدید',
        'success' => 'مسدودیت تمدید شد',
        'form' => [
            'duration' => 'تمدید به مدت',
        ],
        'options' => [
            'hour' => '۱ ساعت',
            'day' => '۱ روز',
            'week' => '۱ هفته',
            'month' => '۱ ماه',
        ],
    ],
    'purge' => [
        'label' => 'حذف منقضی‌شده‌ها',
        'success' => 'مسدودیت‌های منقضی‌شده حذف شدند',
    ],
    'export' => [
        'completed' => 'خروجی مسدودیت‌ها کامل شد و :count ردیف صادر شد.',
        'failed' => ':count ردیف صادر نشد.',
    ],
];
