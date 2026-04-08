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
- [DTO-only mode](use-cases/dto-only.md)
- [Storage](use-cases/storage.md)
- [Public sharing](use-cases/public-sharing.md)
- [Scramble documentation](use-cases/scramble.md)

## Core principles

- HTTP endpoints use `uuid_identifier` rather than numeric IDs.
- Controllers are `__invoke` single-action classes.
- Business logic lives in action classes.
- PDF rendering works with Eloquent models or with explicit DTOs.
- The package can run in database-backed mode or in DTO-only mode for document generation.

## Important defaults

- PHP 8.3 is required.
- Laravel 13 is the target framework.
- API documentation is optional and lives on a configurable path.
- Invoice storage is configurable through a Laravel filesystem disk.
- AI, MCP, and Filament docs are handled in their own packages.
