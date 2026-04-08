# Invoices

Invoices are the core document type of the package.

## Draft invoice

Draft creation accepts a structured DTO:

```php
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

$draft = new InvoiceDraftData(
    seller: new CompanyIdentitySnapshot(legalName: 'ProovIT SAS', displayName: 'ProovIT'),
    customer: new CustomerIdentitySnapshot(legalName: 'Acme Ltd', reference: 'ACME-001'),
    lines: [
        new InvoiceLineData(
            description: 'Consulting services',
            quantity: new LineQuantity('2'),
            unitPrice: new UnitPrice(Money::fromDecimal('125.00', 'EUR')),
            taxRate: Percentage::fromDecimal('20'),
        ),
    ],
    type: InvoiceType::Invoice,
);

$invoice = app(CreateDraftInvoiceAction::class)->handle($draft, $companyId, $customerId);
```

The package calculates:

- subtotal
- tax total
- total

## Finalization

Finalization locks the invoice number and moves the document out of draft state.

## Web preview

Invoices can also be rendered in the browser through the preview and print routes.

See [Web preview and print](web-preview.md).

## Payments

Payments are recorded separately and can be allocated to invoices later.

## Credit notes

A credit note can be created from an invoice to represent a refund or a correction.

## Storage

Generated invoice files can be saved to a configured filesystem disk and directory.

See [Storage](storage.md).
