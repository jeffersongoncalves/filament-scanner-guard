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
        'recent_bans' => 'Bloqueos (últimos :days días)',
        'repeat_offenders' => 'Reincidentes activos',
    ],
    'table' => [
        'ip_hash' => 'Hash de IP',
        'reason' => 'Motivo',
        'matched_value' => 'Valor coincidente',
        'hit_count' => 'Impactos',
        'banned_at' => 'Bloqueado el',
        'expires_at' => 'Expira el',
        'is_active' => 'Activo',
        'offense' => 'Reincidencia',
    ],
    'reason' => [
        'scanner_path' => 'Ruta de escáner',
        'asn_blocklist' => 'Lista de bloqueo ASN',
        'not_found_flood' => 'Flood de 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Manual',
    ],
    'ban_ip' => [
        'label' => 'Bloquear IP',
        'success' => 'IP bloqueada',
        'form' => [
            'ip' => 'Dirección IP',
        ],
    ],
    'blocklist' => [
        'label' => 'Entrada de la lista de bloqueo',
        'plural_label' => 'Lista de bloqueo',
        'type' => 'Tipo',
        'value' => 'Valor',
        'note' => 'Nota',
        'created_at' => 'Añadido el',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Patrón de User-Agent',
        ],
        'help' => [
            'ip' => 'Una dirección IPv4/IPv6 o un rango CIDR, p. ej. 185.177.72.0/24. Bloqueo permanente.',
            'user_agent' => 'Patrón fnmatch, sin distinguir mayúsculas, p. ej. *zgrab*. Se compara con el User-Agent completo.',
        ],
        'invalid_ip' => 'Introduce una dirección IP o un rango CIDR válido.',
    ],
    'filter' => [
        'label' => 'Estado',
        'placeholder' => 'Todos',
        'true_label' => 'Activo',
        'false_label' => 'Expirado',
        'repeat_offenders' => 'Reincidentes',
    ],
    'unban' => [
        'label' => 'Desbloquear',
        'bulk_label' => 'Desbloquear seleccionados',
        'success' => 'Desbloqueado',
    ],
    'charts' => [
        'bans_per_day' => 'Bloqueos por día (últimos :days días)',
        'bans_by_reason' => 'Bloqueos por motivo',
        'top_matched_values' => 'Principales valores coincidentes',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Detalles del bloqueo',
        ],
    ],
    'extend' => [
        'label' => 'Extender',
        'success' => 'Bloqueo extendido',
        'form' => [
            'duration' => 'Extender por',
        ],
        'options' => [
            'hour' => '1 hora',
            'day' => '1 día',
            'week' => '1 semana',
            'month' => '1 mes',
        ],
    ],
    'purge' => [
        'label' => 'Purgar expirados',
        'success' => 'Bloqueos expirados purgados',
    ],
];
