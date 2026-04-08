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

## System

- `GET /status`

Use this for package health checks or smoke tests.

## Response conventions

- JSON resources are grouped by concern
- numeric IDs remain internal
- public HTTP calls should prefer UUIDs
- business logic is delegated to action classes

## Form requests

Validation is kept in `src/Http/Requests/Api`.

That keeps controllers thin and makes each endpoint easier to document and test.
