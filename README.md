# Proovit Laravel Billing

Laravel 13 billing package for invoices, quotes, payments, credit notes, public invoice sharing, PDF rendering, and API-driven billing workflows.

## What this package gives you

- Eloquent models with a stable `uuid_identifier` route key
- Single-action HTTP controllers grouped by concern
- Form requests and JSON resources split by domain
- Draft, finalize, payment, credit note, quote, and quote-to-invoice flows
- A PDF pipeline that works with Eloquent models or with plain DTOs
- Optional database-backed mode or DTO-only mode
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

### DTO-only PDF generation

```php
$draft = new \Proovit\Billing\DTOs\InvoiceDraftData(
    seller: new \Proovit\Billing\ValueObjects\CompanyIdentitySnapshot(
        legalName: 'ProovIT SAS',
        displayName: 'ProovIT',
    ),
    customer: new \Proovit\Billing\ValueObjects\CustomerIdentitySnapshot(
        legalName: 'Acme Ltd',
        reference: 'ACME-001',
    ),
    lines: [
        new \Proovit\Billing\DTOs\InvoiceLineData(
            description: 'Consulting',
            quantity: new \Proovit\Billing\ValueObjects\LineQuantity('1'),
            unitPrice: new \Proovit\Billing\ValueObjects\UnitPrice(
                \Proovit\Billing\ValueObjects\Money::fromDecimal('250.00', 'EUR')
            ),
            taxRate: \Proovit\Billing\ValueObjects\Percentage::fromDecimal('20'),
        ),
    ],
);

$totals = app(\Proovit\Billing\Contracts\InvoiceCalculatorInterface::class)->calculate($draft);

$document = \Proovit\Billing\DTOs\Documents\InvoiceDocumentData::fromDraft(
    $draft,
    $totals,
    [
        'number' => 'INV-2026-0001',
        'issued_at' => now(),
        'due_at' => now()->addDays(30),
        'locale' => 'en',
    ]
);

$pdf = app(\Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction::class)->handle($document);
```

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
- [DTO-only mode](docs/use-cases/dto-only.md)
- [Storage](docs/use-cases/storage.md)
- [Public sharing](docs/use-cases/public-sharing.md)
- [Scramble documentation](docs/use-cases/scramble.md)

## Repository layout

- `src/Actions/Customers` for customer workflows
- `src/Actions/Invoices` for invoice, payment, credit note, and PDF workflows
- `src/Actions/Quotes` for quote workflows and quote conversion
- `src/Http/Controllers/Api` for API controllers
- `src/Http/Requests/Api` for API validation
- `src/Http/Resources/Api` for JSON responses
- `resources/views/pdf` for the PDF HTML view
- `docs/` for package documentation

## Release notes

### Unreleased

- Added optional database-backed and DTO-only billing modes
- Added PDF document DTOs and offline PDF generation support
- Added configurable invoice storage disk and directory
- Added optional resource publication in the installer
- Added Scramble grouping by concern
- Added English docs with user-facing examples

## Notes

- HTTP access uses `uuid_identifier`, not the numeric primary key.
- Controllers are single-action `__invoke` classes.
- Scramble docs are exposed on a configurable path so they do not overwrite the host application's docs.
- AI, MCP, and Filament documentation belongs to their respective packages, not this one.
