<?php

return [
    'navigation' => [
        'group' => 'Skaner Qoruyucu',
    ],
    'resource' => [
        'label' => 'Skaner Qoruyucu Banı',
        'plural_label' => 'Skaner Qoruyucu Banları',
    ],
    'metrics' => [
        'title' => 'Metrika',
    ],
    'stats' => [
        'total_bans' => 'Cəmi Banlar',
        'active_bans' => 'Aktiv Banlar',
        'expired_bans' => 'Vaxtı Bitmiş Banlar',
        'total_hits' => 'Cəmi Toxunuşlar',
        'recent_bans' => 'Banlar (son :days gün)',
        'repeat_offenders' => 'Aktiv təkrar pozucular',
    ],
    'table' => [
        'ip_hash' => 'IP Heşi',
        'reason' => 'Səbəb',
        'matched_value' => 'Uyğun Dəyər',
        'hit_count' => 'Toxunuşlar',
        'banned_at' => 'Ban Tarixi',
        'expires_at' => 'Bitmə Tarixi',
        'is_active' => 'Aktiv',
        'offense' => 'Pozuntu',
    ],
    'reason' => [
        'scanner_path' => 'Skaner Yolu',
        'asn_blocklist' => 'ASN Qara Siyahısı',
        'not_found_flood' => '404 axını',
        'user_agent' => 'User-Agent',
        'manual' => 'Əl ilə',
    ],
    'ban_ip' => [
        'label' => 'IP-ni banla',
        'success' => 'IP banlandı',
        'form' => [
            'ip' => 'IP ünvanı',
        ],
    ],
    'blocklist' => [
        'label' => 'Bloklama siyahısı elementi',
        'plural_label' => 'Bloklama siyahısı',
        'type' => 'Növ',
        'value' => 'Dəyər',
        'note' => 'Qeyd',
        'created_at' => 'Əlavə edilib',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent şablonu',
        ],
        'help' => [
            'ip' => 'IPv4/IPv6 ünvanı və ya CIDR aralığı, məs. 185.177.72.0/24. Daimi bloklanır.',
            'user_agent' => 'fnmatch şablonu, böyük-kiçik hərfə həssas deyil, məs. *zgrab*. Tam User-Agent ilə müqayisə olunur.',
        ],
        'invalid_ip' => 'Düzgün IP ünvanı və ya CIDR aralığı daxil edin.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Hamısı',
        'true_label' => 'Aktiv',
        'false_label' => 'Vaxtı bitmiş',
        'repeat_offenders' => 'Təkrar pozucular',
    ],
    'unban' => [
        'label' => 'Bandan çıxar',
        'bulk_label' => 'Seçilənləri bandan çıxar',
        'success' => 'Bandan çıxarıldı',
    ],
    'charts' => [
        'bans_per_day' => 'Günlük banlar (son :days gün)',
        'bans_by_reason' => 'Səbəbə görə banlar',
        'top_matched_values' => 'Top uyğun dəyərlər',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Ban təfərrüatları',
        ],
    ],
    'extend' => [
        'label' => 'Uzat',
        'success' => 'Ban uzadıldı',
        'form' => [
            'duration' => 'Müddəti uzat',
        ],
        'options' => [
            'hour' => '1 saat',
            'day' => '1 gün',
            'week' => '1 həftə',
            'month' => '1 ay',
        ],
    ],
    'purge' => [
        'label' => 'Vaxtı bitmişləri təmizlə',
        'success' => 'Vaxtı bitmiş banlar təmizləndi',
    ],
];
