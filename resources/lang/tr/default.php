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
];
