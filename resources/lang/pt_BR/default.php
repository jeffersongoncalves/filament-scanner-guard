<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Bloqueio do Scanner Guard',
        'plural_label' => 'Bloqueios do Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Métricas',
    ],
    'stats' => [
        'total_bans' => 'Total de Bloqueios',
        'active_bans' => 'Bloqueios Ativos',
        'expired_bans' => 'Bloqueios Expirados',
        'total_hits' => 'Total de Ocorrências',
    ],
    'table' => [
        'ip_hash' => 'Hash do IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valor Correspondido',
        'hit_count' => 'Ocorrências',
        'banned_at' => 'Bloqueado em',
        'expires_at' => 'Expira em',
        'is_active' => 'Ativo',
    ],
    'reason' => [
        'scanner_path' => 'Caminho de Scanner',
        'asn_blocklist' => 'Lista de Bloqueio de ASN',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Todos',
        'true_label' => 'Ativos',
        'false_label' => 'Expirados',
    ],
    'unban' => [
        'label' => 'Desbloquear',
        'bulk_label' => 'Desbloquear selecionados',
        'success' => 'Desbloqueado',
    ],
    'charts' => [
        'bans_per_day' => 'Bloqueios por dia (últimos 14 dias)',
        'bans_by_reason' => 'Bloqueios por motivo',
        'top_matched_values' => 'Principais valores correspondidos',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Detalhes do bloqueio',
        ],
    ],
    'extend' => [
        'label' => 'Estender',
        'success' => 'Bloqueio estendido',
        'form' => [
            'duration' => 'Estender por',
        ],
        'options' => [
            'hour' => '1 hora',
            'day' => '1 dia',
            'week' => '1 semana',
            'month' => '1 mês',
        ],
    ],
    'purge' => [
        'label' => 'Limpar expirados',
        'success' => 'Bloqueios expirados removidos',
    ],
    'export' => [
        'completed' => 'Sua exportação de bloqueios foi concluída, :count linhas exportadas.',
        'failed' => ':count linhas falharam ao exportar.',
    ],
];
