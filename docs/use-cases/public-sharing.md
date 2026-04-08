# Public Sharing

Public invoice sharing is designed for read-only invoice access.

## When to use it

- send a client a temporary read-only link
- avoid exposing the entire API
- provide a human-friendly invoice view outside your authenticated application

## Example

```php
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;

$url = app(GenerateInvoiceShareLinkAction::class)->handle($invoice);
```

To rotate the link, call the API with `regenerate=true`.

To revoke it completely, call the revoke endpoint.

## Security model

- the link is signed
- the link can expire
- the link can be rotated
- the link can be revoked
- the link is separate from the authenticated API
- the link should not replace your auth strategy

## Configuration

See:

- `billing.public_shares.enabled`
- `billing.public_shares.expires_days`

If the database-backed stack is disabled, public sharing is automatically disabled too.
