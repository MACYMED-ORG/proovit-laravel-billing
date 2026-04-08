# Document Builder Mode

The fluent builder is the recommended way to generate billing documents without relying on local billing tables.

If your application already has a normalized billing payload, you can still drop down to the immutable DTO layer directly. The package supports both approaches.

## When to use it

- you generate invoices from an external ERP
- you build PDFs from queued jobs or webhooks
- you do not want local billing tables
- you do not need the API

## How it works

1. Fill an `InvoiceDocumentBuilder` step by step.
2. Validate the payload.
3. Build the immutable `InvoiceDocumentData`.
4. Render, stream or store the PDF.

## Example

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;

$document = InvoiceDocumentBuilder::make()
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

$html = app(GenerateInvoicePdfAction::class)->handle($document);
```

## Important limitations

- the API is disabled automatically
- public sharing is disabled automatically
- Scramble docs are disabled automatically
- anything that depends on local persistence must stay off

## Low-level DTO mode

If you already have a fully normalized draft and totals object, you can still use the immutable DTO directly:

```php
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;

$document = InvoiceDocumentData::fromDraft($draft, $totals, [
    'number' => 'INV-2026-0001',
    'issued_at' => now(),
    'due_at' => now()->addDays(30),
    'locale' => 'en',
]);
```
