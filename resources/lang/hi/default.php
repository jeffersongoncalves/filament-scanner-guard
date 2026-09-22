<?php

return [
    'navigation' => [
        'group' => 'स्कैनर गार्ड',
    ],
    'resource' => [
        'label' => 'स्कैनर गार्ड प्रतिबंध',
        'plural_label' => 'स्कैनर गार्ड प्रतिबंध',
    ],
    'metrics' => [
        'title' => 'मेट्रिक्स',
    ],
    'stats' => [
        'total_bans' => 'कुल प्रतिबंध',
        'active_bans' => 'सक्रिय प्रतिबंध',
        'expired_bans' => 'समाप्त प्रतिबंध',
        'total_hits' => 'कुल हिट्स',
    ],
    'table' => [
        'ip_hash' => 'IP हैश',
        'reason' => 'कारण',
        'matched_value' => 'मिलान मान',
        'hit_count' => 'हिट्स',
        'banned_at' => 'प्रतिबंधित तिथि',
        'expires_at' => 'समाप्ति तिथि',
        'is_active' => 'सक्रिय',
    ],
    'reason' => [
        'scanner_path' => 'स्कैनर पथ',
        'asn_blocklist' => 'ASN ब्लॉकलिस्ट',
    ],
    'filter' => [
        'label' => 'स्थिति',
        'placeholder' => 'सभी',
        'true_label' => 'सक्रिय',
        'false_label' => 'समाप्त',
    ],
    'unban' => [
        'label' => 'प्रतिबंध हटाएं',
        'bulk_label' => 'चयनित का प्रतिबंध हटाएं',
        'success' => 'प्रतिबंध हटाया गया',
    ],
    'charts' => [
        'bans_per_day' => 'प्रतिदिन प्रतिबंध (पिछले 14 दिन)',
        'bans_by_reason' => 'कारण अनुसार प्रतिबंध',
        'top_matched_values' => 'शीर्ष मिलान मान',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'प्रतिबंध विवरण',
        ],
    ],
    'extend' => [
        'label' => 'बढ़ाएं',
        'success' => 'प्रतिबंध बढ़ाया गया',
        'form' => [
            'duration' => 'अवधि बढ़ाएं',
        ],
        'options' => [
            'hour' => '1 घंटा',
            'day' => '1 दिन',
            'week' => '1 सप्ताह',
            'month' => '1 महीना',
        ],
    ],
    'purge' => [
        'label' => 'समाप्त हटाएं',
        'success' => 'समाप्त प्रतिबंध हटाए गए',
    ],
];
