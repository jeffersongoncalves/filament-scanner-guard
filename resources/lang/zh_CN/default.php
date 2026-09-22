<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Scanner Guard 禁令',
        'plural_label' => 'Scanner Guard 禁令',
    ],
    'metrics' => [
        'title' => '指标',
    ],
    'stats' => [
        'total_bans' => '禁令总数',
        'active_bans' => '生效禁令',
        'expired_bans' => '过期禁令',
        'total_hits' => '命中总数',
    ],
    'table' => [
        'ip_hash' => 'IP 哈希',
        'reason' => '原因',
        'matched_value' => '匹配值',
        'hit_count' => '命中次数',
        'banned_at' => '封禁时间',
        'expires_at' => '到期时间',
        'is_active' => '生效中',
    ],
    'reason' => [
        'scanner_path' => '扫描器路径',
        'asn_blocklist' => 'ASN 黑名单',
    ],
    'filter' => [
        'label' => '状态',
        'placeholder' => '全部',
        'true_label' => '生效中',
        'false_label' => '已过期',
    ],
    'unban' => [
        'label' => '解禁',
        'bulk_label' => '解禁所选',
        'success' => '已解禁',
    ],
];
