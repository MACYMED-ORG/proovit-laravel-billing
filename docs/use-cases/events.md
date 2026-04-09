# Events

`laravel-billing` dispatches domain events for its main workflow steps.

They are useful for:

- notifications
- audit trail recording
- external synchronization
- webhooks and automation jobs

## Available events

- `InvoiceDraftCreated`
- `InvoiceDraftUpdated`
- `InvoiceFinalized`
- `PaymentRegistered`
- `CreditNoteCreated`
- `ReminderRecorded`

The quote-to-invoice workflow is also covered through the quote conversion action and its resulting invoice events.

## Common listeners

Typical listeners include:

- send an email when an invoice is finalized
- export a quote conversion to your CRM
- append an immutable event in another archive
- queue a PDF snapshot generation job

## Example

```php
use Proovit\Billing\Events\InvoiceFinalized;

final class SendInvoiceNotification
{
    public function handle(InvoiceFinalized $event): void
    {
        // notify the customer, sync an ERP, or enqueue an automation
    }
}
```

## Registration

Register the listeners in your application's `EventServiceProvider`.
You can also wire them from your own package if you want a reusable integration layer.
