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

## Render from the fluent builder

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;

$document = InvoiceDocumentBuilder::make()
    ->withSeller([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
    ])
    ->withCustomer([
        'legal_name' => 'Client SARL',
        'reference' => 'CLI-001',
    ])
    ->addLine([
        'description' => 'Consulting services',
        'quantity' => '1',
        'unit_price' => '100.00',
        'tax_rate' => '20',
    ])
    ->withNumber('INV-2026-0001')
    ->withIssuedAt(now())
    ->withDueAt(now()->addDays(30))
    ->validate()
    ->build();

$html = app(GenerateInvoicePdfAction::class)->handle($document);
```

The package still accepts `InvoiceDocumentData` directly when you already have a normalized draft and totals.

## Render from normalized DTOs

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;

$documentDto = InvoiceDocumentData::fromDraft($draft, $totals, [
    'number' => 'INV-2026-0001',
    'issued_at' => now(),
    'due_at' => now()->addDays(30),
    'locale' => 'fr',
]);

$html = app(GenerateInvoicePdfAction::class)->handle($documentDto);
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
