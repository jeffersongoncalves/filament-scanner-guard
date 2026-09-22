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
    ],
    'table' => [
        'ip_hash' => 'IP Heşi',
        'reason' => 'Səbəb',
        'matched_value' => 'Uyğun Dəyər',
        'hit_count' => 'Toxunuşlar',
        'banned_at' => 'Ban Tarixi',
        'expires_at' => 'Bitmə Tarixi',
        'is_active' => 'Aktiv',
    ],
    'reason' => [
        'scanner_path' => 'Skaner Yolu',
        'asn_blocklist' => 'ASN Qara Siyahısı',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Hamısı',
        'true_label' => 'Aktiv',
        'false_label' => 'Vaxtı bitmiş',
    ],
    'unban' => [
        'label' => 'Bandan çıxar',
        'bulk_label' => 'Seçilənləri bandan çıxar',
        'success' => 'Bandan çıxarıldı',
    ],
    'charts' => [
        'bans_per_day' => 'Günlük banlar (son 14 gün)',
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
