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
        'recent_bans' => '封禁（最近:days天）',
        'repeat_offenders' => '活跃的重复违规者',
    ],
    'table' => [
        'ip_hash' => 'IP 哈希',
        'reason' => '原因',
        'matched_value' => '匹配值',
        'hit_count' => '命中次数',
        'banned_at' => '封禁时间',
        'expires_at' => '到期时间',
        'is_active' => '生效中',
        'offense' => '违规次数',
    ],
    'reason' => [
        'scanner_path' => '扫描器路径',
        'asn_blocklist' => 'ASN 黑名单',
        'not_found_flood' => '404 洪泛',
        'user_agent' => 'User-Agent',
        'manual' => '手动',
    ],
    'ban_ip' => [
        'label' => '封禁 IP',
        'success' => 'IP 已封禁',
        'form' => [
            'ip' => 'IP 地址',
        ],
    ],
    'blocklist' => [
        'label' => '黑名单条目',
        'plural_label' => '黑名单',
        'type' => '类型',
        'value' => '值',
        'note' => '备注',
        'created_at' => '添加时间',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent 模式',
        ],
        'help' => [
            'ip' => 'IPv4/IPv6 地址或 CIDR 范围，例如 185.177.72.0/24。永久封禁。',
            'user_agent' => 'fnmatch 模式，不区分大小写，例如 *zgrab*。与完整的 User-Agent 匹配。',
        ],
        'invalid_ip' => '请输入有效的 IP 地址或 CIDR 范围。',
    ],
    'filter' => [
        'label' => '状态',
        'placeholder' => '全部',
        'true_label' => '生效中',
        'false_label' => '已过期',
        'repeat_offenders' => '重复违规者',
    ],
    'unban' => [
        'label' => '解禁',
        'bulk_label' => '解禁所选',
        'success' => '已解禁',
    ],
    'charts' => [
        'bans_per_day' => '每日封禁（最近:days天）',
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
