<?php

return [
    'navigation' => [
        'group' => 'حارس الفحص',
    ],
    'resource' => [
        'label' => 'حظر حارس الفحص',
        'plural_label' => 'حظر حارس الفحص',
    ],
    'metrics' => [
        'title' => 'المقاييس',
    ],
    'stats' => [
        'total_bans' => 'إجمالي الحظر',
        'active_bans' => 'الحظر النشط',
        'expired_bans' => 'الحظر المنتهي',
        'total_hits' => 'إجمالي الإصابات',
    ],
    'table' => [
        'ip_hash' => 'بصمة IP',
        'reason' => 'السبب',
        'matched_value' => 'القيمة المطابقة',
        'hit_count' => 'الإصابات',
        'banned_at' => 'تاريخ الحظر',
        'expires_at' => 'تاريخ الانتهاء',
        'is_active' => 'نشط',
    ],
    'reason' => [
        'scanner_path' => 'مسار الفحص',
        'asn_blocklist' => 'قائمة حظر ASN',
    ],
    'filter' => [
        'label' => 'الحالة',
        'placeholder' => 'الكل',
        'true_label' => 'نشط',
        'false_label' => 'منتهي',
    ],
    'unban' => [
        'label' => 'إلغاء الحظر',
        'bulk_label' => 'إلغاء حظر المحدد',
        'success' => 'تم إلغاء الحظر',
    ],
    'charts' => [
        'bans_per_day' => 'الحظر اليومي (آخر 14 يومًا)',
        'bans_by_reason' => 'الحظر حسب السبب',
        'top_matched_values' => 'أعلى القيم المطابقة',
    ],
];
