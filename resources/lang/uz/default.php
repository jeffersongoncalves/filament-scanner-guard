<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Scanner Guard bloğu',
        'plural_label' => 'Scanner Guard blokları',
    ],
    'metrics' => [
        'title' => 'Ölçülər',
    ],
    'stats' => [
        'total_bans' => 'Ümumi bloklar',
        'active_bans' => 'Aktiv bloklar',
        'expired_bans' => 'Vaxtı bitmiş bloklar',
        'total_hits' => 'Ümumi vuruşlar',
    ],
    'table' => [
        'ip_hash' => 'IP heşi',
        'reason' => 'Səbəb',
        'matched_value' => 'Uyğun dəyər',
        'hit_count' => 'Vuruşlar',
        'banned_at' => 'Blok tarixi',
        'expires_at' => 'Bitmə tarixi',
        'is_active' => 'Aktiv',
    ],
    'reason' => [
        'scanner_path' => 'Skaner yolu',
        'asn_blocklist' => 'ASN blok siyahısı',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Hamısı',
        'true_label' => 'Aktiv',
        'false_label' => 'Vaxtı bitmiş',
    ],
    'unban' => [
        'label' => 'Blokdan çıxar',
        'bulk_label' => 'Seçilənləri blokdan çıxar',
        'success' => 'Blokdan çıxarıldı',
    ],
    'charts' => [
        'bans_per_day' => 'Kunlik bloklar (songgi 14 kun)',
        'bans_by_reason' => 'Sabab boyicha bloklar',
        'top_matched_values' => 'Top mos qiymatlar',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Blok tafsilotlari',
        ],
    ],
    'extend' => [
        'label' => 'Uzatish',
        'success' => 'Blok uzaytirildi',
        'form' => [
            'duration' => 'Muddatni uzaytirish',
        ],
        'options' => [
            'hour' => '1 soat',
            'day' => '1 kun',
            'week' => '1 hafta',
            'month' => '1 oy',
        ],
    ],
    'purge' => [
        'label' => 'Muddati otganlarni tozalash',
        'success' => 'Muddati otgan bloklar tozalandi',
    ],
    'export' => [
        'completed' => 'Bloklar eksporti yakunlandi, :count qator eksport qilindi.',
        'failed' => ':count qator eksport qilinmadi.',
    ],
];
