<?php

return [
    'resource' => [
        'label' => 'Bloqueio do Scanner Guard',
        'plural_label' => 'Bloqueios do Scanner Guard',
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
