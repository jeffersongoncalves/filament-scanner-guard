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
        'recent_bans' => 'प्रतिबंध (पिछले :days दिन)',
        'repeat_offenders' => 'सक्रिय बार-बार उल्लंघनकर्ता',
    ],
    'table' => [
        'ip_hash' => 'IP हैश',
        'reason' => 'कारण',
        'matched_value' => 'मिलान मान',
        'hit_count' => 'हिट्स',
        'banned_at' => 'प्रतिबंधित तिथि',
        'expires_at' => 'समाप्ति तिथि',
        'is_active' => 'सक्रिय',
        'offense' => 'उल्लंघन',
    ],
    'reason' => [
        'scanner_path' => 'स्कैनर पथ',
        'asn_blocklist' => 'ASN ब्लॉकलिस्ट',
        'not_found_flood' => '404 बाढ़',
        'user_agent' => 'User-Agent',
        'manual' => 'मैनुअल',
    ],
    'ban_ip' => [
        'label' => 'IP प्रतिबंधित करें',
        'success' => 'IP प्रतिबंधित किया गया',
        'form' => [
            'ip' => 'IP पता',
        ],
    ],
    'blocklist' => [
        'label' => 'ब्लॉकलिस्ट प्रविष्टि',
        'plural_label' => 'ब्लॉकलिस्ट',
        'type' => 'प्रकार',
        'value' => 'मान',
        'note' => 'नोट',
        'created_at' => 'जोड़ा गया',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent पैटर्न',
        ],
        'help' => [
            'ip' => 'IPv4/IPv6 पता या CIDR रेंज, जैसे 185.177.72.0/24. स्थायी रूप से ब्लॉक।',
            'user_agent' => 'fnmatch पैटर्न, केस-असंवेदनशील, जैसे *zgrab*. पूरे User-Agent से मिलाया जाता है।',
        ],
        'invalid_ip' => 'मान्य IP पता या CIDR रेंज दर्ज करें।',
    ],
    'filter' => [
        'label' => 'स्थिति',
        'placeholder' => 'सभी',
        'true_label' => 'सक्रिय',
        'false_label' => 'समाप्त',
        'repeat_offenders' => 'बार-बार उल्लंघनकर्ता',
    ],
    'unban' => [
        'label' => 'प्रतिबंध हटाएं',
        'bulk_label' => 'चयनित का प्रतिबंध हटाएं',
        'success' => 'प्रतिबंध हटाया गया',
    ],
    'charts' => [
        'bans_per_day' => 'प्रतिदिन प्रतिबंध (पिछले :days दिन)',
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
