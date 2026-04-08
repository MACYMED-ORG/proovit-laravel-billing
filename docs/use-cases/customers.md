# Customers

Use the customer API when you need to manage billing contacts from your application or from an external system.

## Typical workflow

1. Create the customer.
2. Fetch it by `uuid_identifier`.
3. Update billing data when the legal entity changes.
4. Remove it only when your data retention policy allows it.

## Example: create a customer

```php
use Proovit\Billing\Actions\Customers\CreateCustomerAction;
use Proovit\Billing\ValueObjects\AddressData;

$customer = app(CreateCustomerAction::class)->handle([
    'company_id' => $companyId,
    'legal_name' => 'Acme Ltd',
    'full_name' => 'Acme Ltd',
    'reference' => 'ACME-001',
    'email' => 'billing@acme.test',
    'billing_address' => (new AddressData(
        line1: '12 Main Street',
        city: 'Paris',
        country: 'FR',
    ))->toArray(),
]);
```

## API guidance

- Prefer UUIDs in URLs and external payloads.
- Keep the numeric ID internal.
- Populate snapshots as early as possible when the document is created.

## Why this matters

Customers are reused by invoices, quotes, payments, reminders, and audit trails. A consistent customer model reduces copy/paste between downstream documents.
