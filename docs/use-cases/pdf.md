# PDF Rendering

The billing package renders invoices through a Blade-based HTML template.

## What it supports

- Eloquent invoice models
- plain PDF document DTOs
- configurable locale
- configurable paper and orientation

## Render from a model

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;

$html = app(GenerateInvoicePdfAction::class)->handle($invoice);
```

## Render from DTOs

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;

$document = InvoiceDocumentData::fromDraft($draft, $totals, [
    'number' => 'INV-2026-0001',
    'issued_at' => now(),
    'due_at' => now()->addDays(30),
]);

$html = app(GenerateInvoicePdfAction::class)->handle($document);
```

## Download or stream

The package also provides response-oriented actions:

- `DownloadInvoicePdfAction`
- `StreamInvoicePdfAction`

## HTML structure

The invoice view is split into concern-based partials:

- header
- summary
- parties
- lines
- payments
- notes and legal mentions
- footer

This makes the document easier to customize and translate.
