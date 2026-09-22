<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Bannissement Scanner Guard',
        'plural_label' => 'Bannissements Scanner Guard',
    ],
    'metrics' => [
        'title' => 'Métriques',
    ],
    'stats' => [
        'total_bans' => 'Bannissements totaux',
        'active_bans' => 'Bannissements actifs',
        'expired_bans' => 'Bannissements expirés',
        'total_hits' => 'Frappes totales',
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Raison',
        'matched_value' => 'Valeur correspondante',
        'hit_count' => 'Frappes',
        'banned_at' => 'Banni le',
        'expires_at' => 'Expire le',
        'is_active' => 'Actif',
    ],
    'reason' => [
        'scanner_path' => 'Chemin du scanner',
        'asn_blocklist' => 'Liste de blocage ASN',
    ],
    'filter' => [
        'label' => 'Statut',
        'placeholder' => 'Tous',
        'true_label' => 'Actif',
        'false_label' => 'Expiré',
    ],
    'unban' => [
        'label' => 'Débannir',
        'bulk_label' => 'Débannir la sélection',
        'success' => 'Débanni',
    ],
    'charts' => [
        'bans_per_day' => 'Bannissements par jour (14 derniers jours)',
        'bans_by_reason' => 'Bannissements par raison',
        'top_matched_values' => 'Principales valeurs correspondantes',
    ],
];
