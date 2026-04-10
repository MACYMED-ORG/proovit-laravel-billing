# Scramble

The package can expose Scramble documentation on a configurable path.

## Default paths

- UI: `docs/api/billing`
- JSON: `docs/api/billing.json`

## Why this matters

Many applications already have their own documentation site. The billing package should not overwrite it.

## Grouping

API controllers are grouped by concern:

- Customers
- Invoices
- Quotes
- System

That grouping is reflected in Scramble through controller attributes.

## Endpoint titles

Each endpoint also has an explicit title and description so the Scramble sidebar and endpoint detail page read like a product manual instead of raw controller names.

Examples:

- `List customers`
- `View customer`
- `Create draft invoice`
- `Finalize invoice`
- `Convert quote to invoice`
- `Package status`

The title is what Scramble shows in the endpoint list, while the description explains the business intent of the operation.

## Returned data

Scramble response annotations are used to describe the actual payload returned by each route:

- customer endpoints return a paginated `CustomerResource` collection or a single `CustomerResource`
- invoice endpoints return an `InvoiceResource`, `PaymentResource`, `CreditNoteResource`, or a small `data` envelope for share-link operations
- quote endpoints return a paginated `QuoteResource` collection, a `QuoteResource`, or an `InvoiceResource` when a quote is converted
- the system endpoint returns a compact status object with `loaded`, `locale`, and `version`

The package uses dedicated sub-resources for nested objects such as `company`, `customer`, `series`, `reservation`, `totals`, and `invoice` references so the generated OpenAPI body mirrors the real JSON shape instead of collapsing to anonymous arrays.

## Installer behavior

The installer can:

- enable Scramble
- install Scramble if needed
- disable the docs when the package is used without the database-backed stack

## Important note

Scramble documentation only makes sense when the API is enabled, which in turn requires the database-backed stack.
