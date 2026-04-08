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

## Installer behavior

The installer can:

- enable Scramble
- install Scramble if needed
- disable the docs when the package is used in DTO-only mode

## Important note

Scramble documentation only makes sense when the API is enabled, which in turn requires the database-backed stack.
