# Configuration

The main configuration file is `config/billing.php`.

## Top-level sections

- `features` for package capabilities
- `database` for database-backed mode
- `companies` for default company settings
- `company_defaults` for company creation defaults
- `numbering` for document sequences
- `invoice` for invoice behavior
- `taxes` for tax defaults
- `api` for the HTTP API
- `web` for web routes
- `public_shares` for signed public links
- `views` for Blade view publishing
- `pdf` for PDF rendering
- `documents` for PDF storage disk and directories
- `factur_x` for e-invoicing export
- `reminders` for reminder automation
- `audit` for audit trail handling
- `docs` for Scramble documentation

## Database mode

```php
'database' => [
    'enabled' => true,
    'connection' => null,
    'load_migrations' => true,
],
```

When `enabled` is `false`:

- API routes are disabled
- web billing routes are disabled
- public invoice sharing is disabled
- Scramble API docs are disabled
- fluent builder-based document rendering remains available

## API mode

```php
'api' => [
    'enabled' => true,
    'prefix' => 'api/billing',
    'version' => 'v1',
    'middleware' => ['api'],
    'auth_middleware' => ['auth:sanctum'],
    'throttle' => 'api',
],
```

Use `auth_middleware` to plug in your own guard, Sanctum, or any custom middleware stack.

## Document storage

```php
'documents' => [
    'disk' => 'public',
    'invoices' => 'billing/invoices',
    'public_visibility' => 'private',
],
```

This controls where generated invoice files are stored.

Typical choices:

- `public` for local testing
- a private S3 disk for production
- a dedicated filesystem disk for invoice archival

## PDF rendering

```php
'pdf' => [
    'enabled' => true,
    'disk' => null,
    'directory' => 'billing/invoices',
    'paper' => 'a4',
    'orientation' => 'portrait',
    'template' => 'billing::welcome',
    'stream' => true,
    'download' => true,
],
```

### Important notes

- `disk` is optional if you want to use the `documents` section instead.
- `directory` defines the default storage path for generated files.
- `stream` and `download` indicate that the package can expose both delivery styles.

## Scramble docs

```php
'docs' => [
    'enabled' => true,
    'name' => 'billing',
    'api_prefix' => 'api/billing',
    'ui_path' => 'docs/api/billing',
    'json_path' => 'docs/api/billing.json',
    'middleware' => ['web'],
    'domain' => null,
],
```

The package exposes its own documentation path, so it does not overwrite the host application's docs.

## Web routes

```php
'web' => [
    'enabled' => true,
    'prefix' => 'billing',
    'middleware' => ['web'],
    'namespaced' => true,
    'home' => true,
    'preview' => true,
    'print' => true,
],
```

The web stack gives you:

- a package landing page
- an authenticated invoice preview page
- a print-friendly invoice page
- a public shared invoice page when signed sharing is enabled

## Example: builder-only configuration

```php
config([
    'billing.database.enabled' => false,
    'billing.api.enabled' => false,
    'billing.web.enabled' => false,
    'billing.public_shares.enabled' => false,
    'billing.docs.enabled' => false,
    'billing.documents.disk' => 'local',
    'billing.documents.invoices' => 'billing/invoices',
]);
```

## Example: fully authenticated stack

```php
config([
    'billing.database.enabled' => true,
    'billing.api.enabled' => true,
    'billing.api.auth_middleware' => ['auth:sanctum'],
    'billing.public_shares.enabled' => true,
    'billing.docs.enabled' => true,
    'billing.documents.disk' => 'private-invoices',
]);
```
