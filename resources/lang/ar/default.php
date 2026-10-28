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
        'recent_bans' => 'الحظر (آخر :days يومًا)',
        'repeat_offenders' => 'المخالفون المتكررون النشطون',
    ],
    'table' => [
        'ip_hash' => 'بصمة IP',
        'reason' => 'السبب',
        'matched_value' => 'القيمة المطابقة',
        'hit_count' => 'الإصابات',
        'banned_at' => 'تاريخ الحظر',
        'expires_at' => 'تاريخ الانتهاء',
        'is_active' => 'نشط',
        'offense' => 'المخالفة',
    ],
    'reason' => [
        'scanner_path' => 'مسار الفحص',
        'asn_blocklist' => 'قائمة حظر ASN',
        'not_found_flood' => 'فيض 404',
    ],
    'filter' => [
        'label' => 'الحالة',
        'placeholder' => 'الكل',
        'true_label' => 'نشط',
        'false_label' => 'منتهي',
        'repeat_offenders' => 'المخالفون المتكررون',
    ],
    'unban' => [
        'label' => 'إلغاء الحظر',
        'bulk_label' => 'إلغاء حظر المحدد',
        'success' => 'تم إلغاء الحظر',
    ],
    'charts' => [
        'bans_per_day' => 'الحظر اليومي (آخر :days يومًا)',
        'bans_by_reason' => 'الحظر حسب السبب',
        'top_matched_values' => 'أعلى القيم المطابقة',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'تفاصيل الحظر',
        ],
    ],
    'extend' => [
        'label' => 'تمديد',
        'success' => 'تم تمديد الحظر',
        'form' => [
            'duration' => 'تمديد بمقدار',
        ],
        'options' => [
            'hour' => 'ساعة واحدة',
            'day' => 'يوم واحد',
            'week' => 'أسبوع واحد',
            'month' => 'شهر واحد',
        ],
    ],
    'purge' => [
        'label' => 'حذف المنتهية',
        'success' => 'تم حذف الحظر المنتهي',
    ],
];
