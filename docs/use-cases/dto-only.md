# DTO-only Mode

DTO-only mode is the right choice when your application already has the data elsewhere and you only want billing document generation.

## When to use it

- you generate invoices from an external ERP
- you build PDFs from queued jobs or webhooks
- you do not want local billing tables
- you do not need the API

## How it works

1. Build `InvoiceDraftData`.
2. Calculate totals.
3. Build `InvoiceDocumentData`.
4. Render the PDF.

## Example

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\DTOs\InvoiceDraftData;

$draft = new InvoiceDraftData(...);
$totals = app(InvoiceCalculatorInterface::class)->calculate($draft);

$document = InvoiceDocumentData::fromDraft($draft, $totals, [
    'number' => 'INV-2026-0001',
    'issued_at' => now(),
    'due_at' => now()->addDays(30),
    'locale' => 'en',
]);

$html = app(GenerateInvoicePdfAction::class)->handle($document);
```

## Important limitations

- the API is disabled automatically
- public sharing is disabled automatically
- Scramble docs are disabled automatically
- anything that depends on local persistence must stay off
