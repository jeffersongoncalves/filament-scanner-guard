<?php

return [
    'navigation' => [
        'group' => 'Tarayıcı Koruması',
    ],
    'resource' => [
        'label' => 'Tarayıcı Koruma Yasağı',
        'plural_label' => 'Tarayıcı Koruma Yasakları',
    ],
    'metrics' => [
        'title' => 'Metrikler',
    ],
    'stats' => [
        'total_bans' => 'Toplam Yasak',
        'active_bans' => 'Aktif Yasak',
        'expired_bans' => 'Süresi Dolmuş Yasak',
        'total_hits' => 'Toplam İsabet',
    ],
    'table' => [
        'ip_hash' => 'IP Hash',
        'reason' => 'Sebep',
        'matched_value' => 'Eşleşen Değer',
        'hit_count' => 'İsabet',
        'banned_at' => 'Yasaklanma Tarihi',
        'expires_at' => 'Bitiş Tarihi',
        'is_active' => 'Aktif',
    ],
    'reason' => [
        'scanner_path' => 'Tarayıcı Yolu',
        'asn_blocklist' => 'ASN Kara Listesi',
    ],
    'filter' => [
        'label' => 'Durum',
        'placeholder' => 'Tümü',
        'true_label' => 'Aktif',
        'false_label' => 'Süresi Dolmuş',
    ],
    'unban' => [
        'label' => 'Yasağı Kaldır',
        'bulk_label' => 'Seçilenlerin yasağını kaldır',
        'success' => 'Yasak kaldırıldı',
    ],
    'charts' => [
        'bans_per_day' => 'Günlük yasak (son 14 gün)',
        'bans_by_reason' => 'Sebebe göre yasak',
        'top_matched_values' => 'En çok eşleşen değerler',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Yasak detayı',
        ],
    ],
    'extend' => [
        'label' => 'Uzat',
        'success' => 'Yasak uzatıldı',
        'form' => [
            'duration' => 'Uzatma süresi',
        ],
        'options' => [
            'hour' => '1 saat',
            'day' => '1 gün',
            'week' => '1 hafta',
            'month' => '1 ay',
        ],
    ],
    'purge' => [
        'label' => 'Süresi dolmuşları temizle',
        'success' => 'Süresi dolmuş yasaklar temizlendi',
    ],
];
