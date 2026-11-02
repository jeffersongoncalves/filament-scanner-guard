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
        'recent_bans' => 'Bannissements (:days derniers jours)',
        'repeat_offenders' => 'Récidivistes actifs',
    ],
    'table' => [
        'ip_hash' => 'Hash IP',
        'reason' => 'Raison',
        'matched_value' => 'Valeur correspondante',
        'hit_count' => 'Frappes',
        'banned_at' => 'Banni le',
        'expires_at' => 'Expire le',
        'is_active' => 'Actif',
        'offense' => 'Récidive',
    ],
    'reason' => [
        'scanner_path' => 'Chemin du scanner',
        'asn_blocklist' => 'Liste de blocage ASN',
        'not_found_flood' => 'Flood de 404',
        'user_agent' => 'User-Agent',
        'manual' => 'Manuel',
    ],
    'ban_ip' => [
        'label' => 'Bannir une IP',
        'success' => 'IP bannie',
        'form' => [
            'ip' => 'Adresse IP',
        ],
    ],
    'blocklist' => [
        'label' => 'Entrée de la liste de blocage',
        'plural_label' => 'Liste de blocage',
        'type' => 'Type',
        'value' => 'Valeur',
        'note' => 'Note',
        'created_at' => 'Ajouté le',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'Motif User-Agent',
        ],
        'help' => [
            'ip' => 'Une adresse IPv4/IPv6 ou une plage CIDR, p. ex. 185.177.72.0/24. Bloquée de façon permanente.',
            'user_agent' => 'Motif fnmatch, insensible à la casse, p. ex. *zgrab*. Comparé au User-Agent complet.',
        ],
        'invalid_ip' => 'Saisissez une adresse IP ou une plage CIDR valide.',
    ],
    'filter' => [
        'label' => 'Statut',
        'placeholder' => 'Tous',
        'true_label' => 'Actif',
        'false_label' => 'Expiré',
        'repeat_offenders' => 'Récidivistes',
    ],
    'unban' => [
        'label' => 'Débannir',
        'bulk_label' => 'Débannir la sélection',
        'success' => 'Débanni',
    ],
    'charts' => [
        'bans_per_day' => 'Bannissements par jour (:days derniers jours)',
        'bans_by_reason' => 'Bannissements par raison',
        'top_matched_values' => 'Principales valeurs correspondantes',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Détails du bannissement',
        ],
    ],
    'extend' => [
        'label' => 'Prolonger',
        'success' => 'Bannissement prolongé',
        'form' => [
            'duration' => 'Prolonger de',
        ],
        'options' => [
            'hour' => '1 heure',
            'day' => '1 jour',
            'week' => '1 semaine',
            'month' => '1 mois',
        ],
    ],
    'purge' => [
        'label' => 'Purger les expirés',
        'success' => 'Bannissements expirés purgés',
    ],
];
