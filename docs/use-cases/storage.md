# Storage

Generated invoice files can be written to any Laravel filesystem disk.

## Configuration

```php
'documents' => [
    'disk' => 'public',
    'invoices' => 'billing/invoices',
],
```

## Example: store a generated PDF

```php
use Proovit\Billing\Actions\Invoices\StoreInvoicePdfAction;

$path = app(StoreInvoicePdfAction::class)->handle($invoice);
```

## Practical recommendations

- use a dedicated disk for production invoices
- keep the disk private if the files contain sensitive data
- store public downloads only when you explicitly want them to be accessible
- use `documents.invoices` to keep the invoice folder organized
