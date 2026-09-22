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
    'charts' => [
        'bans_per_day' => '每日封禁（最近14天）',
        'bans_by_reason' => '按原因统计封禁',
        'top_matched_values' => '匹配值 TOP',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => '封禁详情',
        ],
    ],
    'extend' => [
        'label' => '延长',
        'success' => '封禁已延长',
        'form' => [
            'duration' => '延长时长',
        ],
        'options' => [
            'hour' => '1 小时',
            'day' => '1 天',
            'week' => '1 周',
            'month' => '1 个月',
        ],
    ],
    'purge' => [
        'label' => '清理过期',
        'success' => '过期封禁已清理',
    ],
];
