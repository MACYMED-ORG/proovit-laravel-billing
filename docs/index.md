# Proovit Billing Documentation

This documentation covers the `proovit/laravel-billing` package from a user and integrator perspective.

## Start here

- [Installation](install.md)
- [Configuration](configuration.md)
- [HTTP API](api.md)
- [Authentication](authentication.md)
- [Release notes](release-notes.md)
- [FAQ](faq.md)

## Practical guides

- [Customers](use-cases/customers.md)
- [Invoices](use-cases/invoices.md)
- [Quotes](use-cases/quotes.md)
- [PDF rendering](use-cases/pdf.md)
- [Sample PDF output](assets/invoice-sample.pdf)
- [Web preview and print](use-cases/web-preview.md)
- [Document builder mode](use-cases/document-builder.md)
- [Storage](use-cases/storage.md)
- [Public sharing](use-cases/public-sharing.md)
- [Demo data](use-cases/demo-data.md)
- [Events](use-cases/events.md)
- [Scramble documentation](use-cases/scramble.md)

## Core principles

- HTTP endpoints use `uuid_identifier` rather than numeric IDs.
- Controllers are `__invoke` single-action classes.
- Business logic lives in action classes.
- PDF rendering works with Eloquent models or with explicit DTOs.
- The package can run in database-backed mode or in fluent builder mode for document generation.
- The fluent builder is the recommended public API for non-Eloquent document generation.

## Important defaults

- PHP 8.3 is required.
- Laravel 13 is the target framework.
- API documentation is optional and lives on a configurable path.
- Invoice storage is configurable through a Laravel filesystem disk.
- AI, MCP, and Filament docs are handled in their own packages.
