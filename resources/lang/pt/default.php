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
        'recent_bans' => 'Bloqueios (últimos :days dias)',
        'repeat_offenders' => 'Reincidentes ativos',
    ],
    'table' => [
        'ip_hash' => 'Hash do IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valor Correspondido',
        'hit_count' => 'Ocorrências',
        'banned_at' => 'Bloqueado em',
        'expires_at' => 'Expira em',
        'is_active' => 'Ativo',
        'offense' => 'Reincidência',
    ],
    'reason' => [
        'scanner_path' => 'Caminho de Scanner',
        'asn_blocklist' => 'Lista de Bloqueio de ASN',
        'not_found_flood' => 'Flood de 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Manual',
    ],
    'ban_ip' => [
        'label' => 'Bloquear IP',
        'success' => 'IP bloqueado',
        'form' => [
            'ip' => 'Endereço IP',
        ],
    ],
    'blocklist' => [
        'label' => 'Item da Lista de Bloqueio',
        'plural_label' => 'Lista de Bloqueio',
        'type' => 'Tipo',
        'value' => 'Valor',
        'note' => 'Observação',
        'created_at' => 'Adicionado em',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Padrão de User-Agent',
        ],
        'help' => [
            'ip' => 'Endereço IPv4/IPv6 ou faixa CIDR, ex.: 185.177.72.0/24. Bloqueio permanente.',
            'user_agent' => 'Padrão fnmatch, sem diferenciar maiúsculas, ex.: *zgrab*. Comparado com o User-Agent completo.',
        ],
        'invalid_ip' => 'Informe um endereço IP ou faixa CIDR válido.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Todos',
        'true_label' => 'Ativos',
        'false_label' => 'Expirados',
        'repeat_offenders' => 'Reincidentes',
    ],
    'unban' => [
        'label' => 'Desbloquear',
        'bulk_label' => 'Desbloquear selecionados',
        'success' => 'Desbloqueado',
    ],
    'charts' => [
        'bans_per_day' => 'Bloqueios por dia (últimos :days dias)',
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
];
