<?php

return [
    'navigation' => [
        'group' => 'スキャナーガード',
    ],
    'resource' => [
        'label' => 'スキャナーガード BAN',
        'plural_label' => 'スキャナーガード BAN',
    ],
    'metrics' => [
        'title' => 'メトリクス',
    ],
    'stats' => [
        'total_bans' => 'BAN 合計',
        'active_bans' => '有効な BAN',
        'expired_bans' => '期限切れ BAN',
        'total_hits' => 'ヒット合計',
        'recent_bans' => 'BAN（直近:days日）',
        'repeat_offenders' => 'アクティブな再犯',
    ],
    'table' => [
        'ip_hash' => 'IP ハッシュ',
        'reason' => '理由',
        'matched_value' => '一致した値',
        'hit_count' => 'ヒット数',
        'banned_at' => 'BAN 日時',
        'expires_at' => '有効期限',
        'is_active' => '有効',
        'offense' => '違反回数',
    ],
    'reason' => [
        'scanner_path' => 'スキャナーパス',
        'asn_blocklist' => 'ASN ブロックリスト',
        'not_found_flood' => '404 フラッド',
        'user_agent' => 'User-Agent',
        'manual' => '手動',
    ],
    'ban_ip' => [
        'label' => 'IP を BAN',
        'success' => 'IP を BAN しました',
        'form' => [
            'ip' => 'IP アドレス',
        ],
    ],
    'blocklist' => [
        'label' => 'ブロックリスト項目',
        'plural_label' => 'ブロックリスト',
        'type' => '種類',
        'value' => '値',
        'note' => 'メモ',
        'created_at' => '追加日時',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent パターン',
        ],
        'help' => [
            'ip' => 'IPv4/IPv6 アドレスまたは CIDR 範囲（例: 185.177.72.0/24）。永続的にブロックされます。',
            'user_agent' => 'fnmatch パターン、大文字小文字を区別しません（例: *zgrab*）。User-Agent 全体と照合されます。',
        ],
        'invalid_ip' => '有効な IP アドレスまたは CIDR 範囲を入力してください。',
    ],
    'filter' => [
        'label' => 'ステータス',
        'placeholder' => 'すべて',
        'true_label' => '有効',
        'false_label' => '期限切れ',
        'repeat_offenders' => '再犯',
    ],
    'unban' => [
        'label' => 'BAN 解除',
        'bulk_label' => '選択を BAN 解除',
        'success' => 'BAN を解除しました',
    ],
    'charts' => [
        'bans_per_day' => '日別 BAN（直近:days日）',
        'bans_by_reason' => '理由別 BAN',
        'top_matched_values' => '一致値トップ',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'BAN 詳細',
        ],
    ],
    'extend' => [
        'label' => '延長',
        'success' => 'BAN を延長しました',
        'form' => [
            'duration' => '延長期間',
        ],
        'options' => [
            'hour' => '1時間',
            'day' => '1日',
            'week' => '1週間',
            'month' => '1か月',
        ],
    ],
    'purge' => [
        'label' => '期限切れを削除',
        'success' => '期限切れ BAN を削除しました',
    ],
];
