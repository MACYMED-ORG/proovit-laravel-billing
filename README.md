# Proovit Laravel Billing

Laravel 13 billing package for invoices, quotes, payments, credit notes, public invoice sharing, PDF rendering, and API-driven billing workflows.

## What this package gives you

- Eloquent models with a stable `uuid_identifier` route key
- Single-action HTTP controllers grouped by concern
- Form requests and JSON resources split by domain
- Draft, finalize, payment, credit note, quote, and quote-to-invoice flows
- A PDF pipeline that works with Eloquent models or with plain DTOs
- Optional database-backed mode or fluent document builder mode
- Configurable storage disk and directory for generated invoice files
- Translatable labels and package translations
- Optional Scramble documentation at `docs/api/billing`
- Optional signed public sharing for invoices

## Requirements

- PHP 8.3
- Laravel 13
- Composer

## Install

```bash
composer require proovit/laravel-billing
php artisan billing:install
```

The installer can:

- enable or disable the database-backed stack
- enable or disable the API
- install and configure Sanctum if you want `auth:sanctum`
- publish package resources when requested
- enable or disable signed public invoice links
- enable or disable Scramble documentation

## Quick examples

### Full stack

```php
config([
    'billing.database.enabled' => true,
    'billing.api.enabled' => true,
    'billing.api.auth_middleware' => ['auth:sanctum'],
    'billing.public_shares.enabled' => true,
    'billing.docs.enabled' => true,
]);
```

### Fluent document builder

```php
$document = \Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder::make()
    ->withSeller([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
    ])
    ->withCustomer([
        'legal_name' => 'Acme Ltd',
        'reference' => 'ACME-001',
        'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
    ])
    ->addLine([
        'description' => 'Consulting',
        'quantity' => '1',
        'unit_price' => '250.00',
        'tax_rate' => '20',
    ])
    ->withNumber('INV-2026-0001')
    ->withIssuedAt(now())
    ->withDueAt(now()->addDays(30))
    ->withLocale('en')
    ->validate()
    ->build();

$pdf = app(\Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction::class)->handle($document);
```

If you already have a fully normalized draft and totals object, you can still build the immutable document DTO directly with `InvoiceDocumentData::fromDraft(...)`.

The fluent builder is the recommended public API for applications that do not want to pass a large DTO object around by hand.

## Documentation

- [Installation](docs/install.md)
- [Configuration](docs/configuration.md)
- [HTTP API](docs/api.md)
- [Authentication](docs/authentication.md)
- [Release notes](docs/release-notes.md)
- [FAQ](docs/faq.md)

## Use cases

- [Customers](docs/use-cases/customers.md)
- [Invoices](docs/use-cases/invoices.md)
- [Quotes](docs/use-cases/quotes.md)
- [PDF rendering](docs/use-cases/pdf.md)
- [Web preview and print](docs/use-cases/web-preview.md)
- [Document builder mode](docs/use-cases/document-builder.md)
- [Storage](docs/use-cases/storage.md)
- [Public sharing](docs/use-cases/public-sharing.md)
- [Demo data](docs/use-cases/demo-data.md)
- [Scramble documentation](docs/use-cases/scramble.md)

## Repository layout

- `src/Actions/Customers` for customer workflows
- `src/Actions/Invoices` for invoice, payment, credit note, and PDF workflows
- `src/Actions/Quotes` for quote workflows and quote conversion
- `src/Http/Controllers/Api` for API controllers
- `src/Http/Requests/Api` for API validation
- `src/Http/Resources/Api` for JSON responses
- `src/Http/Controllers/Web` for web preview and public sharing controllers
- `database/factories` for package factories
- `database/seeders` for demo and sample data seeders
- `resources/views/pdf` for the PDF HTML view
- `resources/views/web` for browser preview and print views
- `docs/` for package documentation
- `Makefile` for common local and Sail-based tasks

## Release notes

### 1.0.0

- Added optional database-backed and fluent document builder billing modes
- Added PDF document DTOs and offline PDF generation support
- Added configurable invoice storage disk and directory
- Added optional resource publication in the installer
- Added Scramble grouping by concern
- Added English docs with user-facing examples
- Added web preview and print views for invoices
- Added package factories and a demo seeder
- Added a package-local Makefile for QA and Sail workflows

## Notes

- HTTP access uses `uuid_identifier`, not the numeric primary key.
- Controllers are single-action `__invoke` classes.
- Scramble docs are exposed on a configurable path so they do not overwrite the host application's docs.
- AI, MCP, and Filament documentation belongs to their respective packages, not this one.
- The package `Makefile` only covers package-local QA; sandbox commands live at the repository root.

## Acknowledgements

The package builds on the Laravel ecosystem and related tooling:

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Pest](https://img.shields.io/badge/Pest-222222?style=for-the-badge&logo=pest&logoColor=white)](https://pestphp.com/)
[![Pint](https://img.shields.io/badge/Pint-444444?style=for-the-badge&logo=laravel&logoColor=white)](https://github.com/laravel/pint)
[![PHPStan](https://img.shields.io/badge/PHPStan-0A6EBD?style=for-the-badge&logo=phpstan&logoColor=white)](https://phpstan.org/)
[![Larastan](https://img.shields.io/badge/Larastan-2F855A?style=for-the-badge&logo=laravel&logoColor=white)](https://github.com/larastan/larastan)
[![Scramble](https://img.shields.io/badge/Scramble-111827?style=for-the-badge&logo=swagger&logoColor=white)](https://dedoc.co/docs/scramble)
[![Brick Math](https://img.shields.io/badge/Brick.Math-5B4638?style=for-the-badge)](https://github.com/brick/math)
