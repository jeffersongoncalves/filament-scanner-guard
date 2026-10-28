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
