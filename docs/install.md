# Installation

## Requirements

- PHP 8.3
- Laravel 13
- Composer

## Install the package

```bash
composer require proovit/laravel-billing
php artisan billing:install
```

The installer asks about:

- database-backed billing mode
- API exposure
- API authentication middleware
- Sanctum installation when needed
- signed public invoice sharing
- Scramble API documentation
- optional resource publication

## Resource publication

Publishing package resources is optional.

When you choose not to publish resources:

- the package migrations are still loaded directly from the package
- you can still run the migration files in your application
- the package keeps its own views, translations, and routes in vendor space

This is useful when you want a clean installation or when you only consume the package through code.

## Database-backed mode

If you enable the database-backed stack:

- API routes are enabled
- web invoice sharing can be enabled
- audit trail and reminders can be enabled
- Scramble documentation can be exposed
- Eloquent models become the primary source of truth

Example:

```php
config([
    'billing.database.enabled' => true,
    'billing.api.enabled' => true,
    'billing.api.auth_middleware' => ['auth:sanctum'],
    'billing.public_shares.enabled' => true,
]);
```

## Document builder mode

If you disable the database-backed stack:

- API routes are disabled automatically
- signed public sharing is disabled automatically
- Scramble documentation is disabled automatically
- PDF generation can still run from the fluent document builder or from normalized DTOs

This mode is useful for:

- invoice previews
- external systems that already own the data
- document generation jobs
- PDF rendering without local persistence

Example:

```php
config([
    'billing.database.enabled' => false,
    'billing.api.enabled' => false,
    'billing.web.enabled' => false,
    'billing.public_shares.enabled' => false,
    'billing.docs.enabled' => false,
]);
```

## After installation

1. Review `config/billing.php`
2. Publish resources later if you need to customize views, translations, or routes
3. Run your migrations if the database stack is enabled
4. Configure Sanctum or your own middleware if the API is exposed
5. Configure Scramble if you want API documentation
6. Use the fluent document builder when you want to generate PDFs without local persistence

## Composer automation

The installer can install dependencies for you:

- `laravel/sanctum`
- `dedoc/scramble`

If installation fails, the command prints the exact Composer command to run manually.
