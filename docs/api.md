# HTTP API

The billing API is split by concern and uses invokable controllers.

## Base path

Default:

```text
/api/billing/v1
```

The base path is configurable in `config/billing.php`.

## Authentication

The package does not force a single auth strategy.

You can use:

- `auth:sanctum`
- your own guard middleware
- a private network with no auth middleware

See [Authentication](authentication.md) for details.

## API groups

- `System`
- `Customers`
- `Invoices`
- `Quotes`

These groups are reflected in Scramble documentation.

### Group descriptions

- `System`: package health and smoke-test endpoints
- `Customers`: billing customers and their related addresses
- `Invoices`: invoices, payments, credit notes, and public share links
- `Quotes`: quotes and quote-to-invoice conversion

## Endpoint titles

The documentation also defines explicit endpoint titles and descriptions so Scramble does not fall back to controller class names.

### Customers

- `List customers`
- `View customer`
- `Create customer`
- `Update customer`
- `Delete customer`

### Invoices

- `List invoices`
- `View invoice`
- `Create draft invoice`
- `Update draft invoice`
- `Finalize invoice`
- `Register invoice payment`
- `Create credit note`
- `Generate invoice share link`
- `Revoke invoice share link`

### Quotes

- `List quotes`
- `View quote`
- `Create quote`
- `Update quote`
- `Delete quote`
- `Convert quote to invoice`

### System

- `Package status`

## Response data at a glance

The generated Scramble docs follow the response shape returned by the controllers and resources:

- customers return customer data with company and address context, including explicit address and company sub-resources
- invoices return seller and customer snapshots, totals, series, quote links, lines, and payments, with nested invoice references and totals rendered as dedicated sub-resources
- quotes return the quote snapshot, line items, totals, and converted invoice linkage, again as explicit nested resources
- system status returns a small `data` object with `loaded`, `locale`, and `version`

## Endpoint response map

This is the quickest way to understand what each endpoint returns:

| Endpoint | Response |
| --- | --- |
| `GET /customers` | paginated `CustomerResource` collection |
| `GET /customers/{customer}` | `CustomerResource` |
| `POST /customers` | `CustomerResource` |
| `PATCH /customers/{customer}` | `CustomerResource` |
| `DELETE /customers/{customer}` | `204 No Content` |
| `GET /invoices` | paginated `InvoiceResource` collection |
| `GET /invoices/{invoice}` | `InvoiceResource` |
| `POST /invoices` | `InvoiceResource` |
| `PATCH /invoices/{invoice}` | `InvoiceResource` |
| `POST /invoices/{invoice}/finalize` | `InvoiceResource` |
| `POST /invoices/{invoice}/payments` | `PaymentResource` |
| `POST /invoices/{invoice}/credit-notes` | `CreditNoteResource` |
| `POST /invoices/{invoice}/share-link` | `{ data: { invoice_uuid_identifier, public_share_url, public_share_token, public_share_expires_at } }` |
| `POST /invoices/{invoice}/share-link/revoke` | `{ data: { invoice_uuid_identifier, public_share_url, public_share_token, public_share_expires_at } }` |
| `GET /quotes` | paginated `QuoteResource` collection |
| `GET /quotes/{quote}` | `QuoteResource` |
| `POST /quotes` | `QuoteResource` |
| `PATCH /quotes/{quote}` | `QuoteResource` |
| `DELETE /quotes/{quote}` | `204 No Content` |
| `POST /quotes/{quote}/convert` | `InvoiceResource` |
| `GET /status` | `{ data: { loaded, locale, version } }` |

## Customers

- `GET /customers`
- `POST /customers`
- `GET /customers/{customer}`
- `PATCH /customers/{customer}`
- `DELETE /customers/{customer}`

Example:

```php
Route::post('customers', StoreCustomerController::class);
```

Returned data:

- `CustomerResource` for `GET /customers/{customer}`
- `CustomerResource` for `POST /customers`
- `CustomerResource` for `PATCH /customers/{customer}`
- `204 No Content` for `DELETE /customers/{customer}`

## Invoices

- `GET /invoices`
- `POST /invoices`
- `GET /invoices/{invoice}`
- `PATCH /invoices/{invoice}`
- `POST /invoices/{invoice}/finalize`
- `POST /invoices/{invoice}/payments`
- `POST /invoices/{invoice}/credit-notes`
- `POST /invoices/{invoice}/share-link`
- `POST /invoices/{invoice}/share-link/revoke`

Returned data:

- `InvoiceResource` for invoice listing and detail endpoints
- `InvoiceResource` for draft creation, update, and finalization
- `PaymentResource` for `POST /invoices/{invoice}/payments`
- `CreditNoteResource` for `POST /invoices/{invoice}/credit-notes`
- `data` payloads for share-link generation and revocation

Example payload for a draft invoice:

```json
{
  "currency": "EUR",
  "seller": {
    "legal_name": "ProovIT SAS",
    "display_name": "ProovIT",
    "email": "billing@example.test"
  },
  "customer": {
    "legal_name": "Acme Ltd",
    "reference": "ACME-001"
  },
  "lines": [
    {
      "description": "Consulting",
      "quantity": "1",
      "unit_price": "250.00",
      "tax_rate": "20"
    }
  ]
}
```

Example payload for a share link:

```json
{
  "regenerate": true,
  "expires_days": 15
}
```

## Quotes

- `GET /quotes`
- `POST /quotes`
- `GET /quotes/{quote}`
- `PATCH /quotes/{quote}`
- `DELETE /quotes/{quote}`
- `POST /quotes/{quote}/convert`

Quote conversion creates a linked invoice and keeps the relationship in the data model.

Returned data:

- `QuoteResource` for listing, detail, creation, and update
- `InvoiceResource` for `POST /quotes/{quote}/convert`
- `204 No Content` for `DELETE /quotes/{quote}`

## System

- `GET /status`

Use this for package health checks or smoke tests.

Returned data:

```json
{
  "data": {
    "loaded": true,
    "locale": "en",
    "version": "v1"
  }
}
```

## Response conventions

- JSON resources are grouped by concern
- numeric IDs remain internal
- public HTTP calls should prefer UUIDs
- business logic is delegated to action classes

## Form requests

Validation is kept in `src/Http/Requests/Api`.

That keeps controllers thin and makes each endpoint easier to document and test.
