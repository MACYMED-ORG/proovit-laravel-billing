<?php

declare(strict_types=1);

return [
    'features' => [
        'database' => true,
        'api' => false,
        'web' => true,
        'views' => true,
        'pdf' => true,
        'factur_x' => true,
        'reminders' => false,
        'audit' => true,
        'ai' => false,
        'mcp' => false,
    ],

    'companies' => [
        'default_currency' => 'EUR',
        'default_locale' => 'fr',
        'timezone' => 'Europe/Paris',
        'default_country' => 'FR',
    ],

    'company_defaults' => [
        'legal_name' => null,
        'display_name' => null,
        'legal_form' => null,
        'registration_country' => 'FR',
        'siren' => null,
        'siret' => null,
        'vat_number' => null,
        'intracommunity_vat_number' => null,
        'naf_ape' => null,
        'rcs_city' => null,
        'head_office_address' => null,
        'billing_address' => null,
        'email' => null,
        'phone' => null,
        'website' => null,
        'default_currency' => 'EUR',
        'default_locale' => 'fr',
        'timezone' => 'Europe/Paris',
        'default_payment_terms' => 30,
        'invoice_prefix' => 'INV',
        'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
        'default_bank_account' => null,
    ],

    'numbering' => [
        'prefix' => 'INV',
        'suffix' => null,
        'pattern' => '{prefix}-{year}{month}-{sequence}',
        'padding' => 6,
        'reset' => 'annual',
        'series' => [
            'invoice' => [
                'prefix' => 'INV',
                'suffix' => null,
                'padding' => 6,
                'reset' => 'annual',
            ],
            'credit_note' => [
                'prefix' => 'CN',
                'suffix' => null,
                'padding' => 6,
                'reset' => 'annual',
            ],
            'quote' => [
                'prefix' => 'QTE',
                'suffix' => null,
                'padding' => 6,
                'reset' => 'annual',
            ],
        ],
    ],

    'invoice' => [
        'default_due_days' => 30,
        'snapshots' => true,
        'rounding_mode' => 'half_up',
        'default_payment_terms' => 30,
        'default_currency' => 'EUR',
        'snapshot_seller' => true,
        'snapshot_customer' => true,
        'legal_mentions' => [
            'vat_exempt' => true,
            'late_payment' => true,
            'penalties' => true,
        ],
    ],

    'taxes' => [
        'default_country' => 'FR',
        'default_rate' => 20.0,
        'vat_enabled' => true,
    ],

    'api' => [
        'enabled' => false,
        'prefix' => 'api/billing',
        'version' => 'v1',
        'middleware' => ['api'],
        'auth_middleware' => [],
        'throttle' => 'api',
    ],

    'web' => [
        'enabled' => true,
        'prefix' => 'billing',
        'middleware' => ['web'],
        'namespaced' => true,
    ],

    'docs' => [
        'enabled' => true,
        'name' => 'billing',
        'api_prefix' => 'api/billing',
        'ui_path' => 'docs/api/billing',
        'json_path' => 'docs/api/billing.json',
        'middleware' => ['web'],
        'domain' => null,
    ],

    'database' => [
        'enabled' => true,
        'connection' => null,
        'load_migrations' => true,
    ],

    'public_shares' => [
        'enabled' => true,
        'expires_days' => 30,
    ],

    'views' => [
        'enabled' => true,
        'namespace' => 'billing',
        'path' => 'billing',
        'publish_tag' => 'billing-views',
    ],

    'pdf' => [
        'enabled' => true,
        'disk' => null,
        'directory' => 'billing/invoices',
        'paper' => 'a4',
        'orientation' => 'portrait',
        'template' => 'billing::welcome',
        'stream' => true,
        'download' => true,
    ],

    'documents' => [
        'disk' => 'public',
        'invoices' => 'billing/invoices',
        'public_visibility' => 'private',
    ],

    'factur_x' => [
        'enabled' => true,
        'profile' => 'basic',
        'embed_pdf' => false,
        'xml_format' => 'factur-x',
        'profile_name' => 'Factur-X Basic',
    ],

    'reminders' => [
        'enabled' => false,
        'channels' => ['mail'],
        'default_delay_days' => 7,
    ],

    'audit' => [
        'enabled' => true,
        'channel' => null,
        'context' => [
            'company_id' => true,
            'user_id' => true,
            'request_id' => true,
        ],
    ],
];
