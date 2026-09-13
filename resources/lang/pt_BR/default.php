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
];
