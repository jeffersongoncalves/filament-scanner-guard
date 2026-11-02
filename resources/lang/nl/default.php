<?php

return [
    'navigation' => [
        'group' => 'Scanner Guard',
    ],
    'resource' => [
        'label' => 'Scanner Guard Ban',
        'plural_label' => 'Scanner Guard Bans',
    ],
    'metrics' => [
        'title' => 'Statistieken',
    ],
    'stats' => [
        'total_bans' => 'Totaal aantal bans',
        'active_bans' => 'Actieve bans',
        'expired_bans' => 'Verlopen bans',
        'total_hits' => 'Totaal aantal hits',
        'recent_bans' => 'Bans (laatste :days dagen)',
        'repeat_offenders' => 'Actieve herhaalde overtreders',
    ],
    'table' => [
        'ip_hash' => 'IP-hash',
        'reason' => 'Reden',
        'matched_value' => 'Overeenkomende waarde',
        'hit_count' => 'Hits',
        'banned_at' => 'Verbannen op',
        'expires_at' => 'Verloopt op',
        'is_active' => 'Actief',
        'offense' => 'Overtreding',
    ],
    'reason' => [
        'scanner_path' => 'Scannerpad',
        'asn_blocklist' => 'ASN-blokkadelĳst',
        'not_found_flood' => '404-vloed',
        'user_agent' => 'User-Agent',
        'manual' => 'Handmatig',
    ],
    'ban_ip' => [
        'label' => 'IP bannen',
        'success' => 'IP geband',
        'form' => [
            'ip' => 'IP-adres',
        ],
    ],
    'blocklist' => [
        'label' => 'Blokkeerlijstitem',
        'plural_label' => 'Blokkeerlijst',
        'type' => 'Type',
        'value' => 'Waarde',
        'note' => 'Notitie',
        'created_at' => 'Toegevoegd op',
        'types' => [
            'ip' => 'IP / CIDR',
            'user_agent' => 'User-Agent-patroon',
        ],
        'help' => [
            'ip' => 'Een IPv4/IPv6-adres of CIDR-bereik, bijv. 185.177.72.0/24. Permanent geblokkeerd.',
            'user_agent' => 'fnmatch-patroon, niet hoofdlettergevoelig, bijv. *zgrab*. Vergeleken met de volledige User-Agent.',
        ],
        'invalid_ip' => 'Voer een geldig IP-adres of CIDR-bereik in.',
    ],
    'filter' => [
        'label' => 'Status',
        'placeholder' => 'Alle',
        'true_label' => 'Actief',
        'false_label' => 'Verlopen',
        'repeat_offenders' => 'Herhaalde overtreders',
    ],
    'unban' => [
        'label' => 'Deblokkeer',
        'bulk_label' => 'Selectie deblokkeren',
        'success' => 'Gedeblokkeerd',
    ],
    'charts' => [
        'bans_per_day' => 'Bans per dag (laatste :days dagen)',
        'bans_by_reason' => 'Bans per reden',
        'top_matched_values' => 'Top overeenkomende waarden',
    ],
    'infolist' => [
        'sections' => [
            'ban_details' => 'Ban-details',
        ],
    ],
    'extend' => [
        'label' => 'Verlengen',
        'success' => 'Ban verlengd',
        'form' => [
            'duration' => 'Verlengen met',
        ],
        'options' => [
            'hour' => '1 uur',
            'day' => '1 dag',
            'week' => '1 week',
            'month' => '1 maand',
        ],
    ],
    'purge' => [
        'label' => 'Verlopen opschonen',
        'success' => 'Verlopen bans opgeschoond',
    ],
];
