<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Bloqueo de Scanner Guard',
        'plural_label' => 'Bloqueos de Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Métricas',
    ],
    'stats' => [
        'total_bans' => 'Bloqueos totales',
        'active_bans' => 'Bloqueos activos',
        'expired_bans' => 'Bloqueos expirados',
        'total_hits' => 'Impactos totales',
    ],
    'table' => [
        'ip_hash' => 'Hash de IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valor coincidente',
        'hit_count' => 'Impactos',
        'banned_at' => 'Bloqueado el',
        'expires_at' => 'Expira el',
        'is_active' => 'Activo',
    ],
    'reason' => [
        'scanner_path' => 'Ruta de escáner',
        'asn_blocklist' => 'Lista de bloqueo ASN',
    ],
    'filter' => [
        'label' => 'Estado',
        'placeholder' => 'Todos',
        'true_label' => 'Activo',
        'false_label' => 'Expirado',
    ],
    'unban' => [
        'label' => 'Desbloquear',
        'bulk_label' => 'Desbloquear seleccionados',
        'success' => 'Desbloqueado',
    ],
];
