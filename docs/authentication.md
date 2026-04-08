# Authentication

The package does not enforce a single authentication system.

## Recommended setup for private APIs

Use Sanctum when your application exposes the billing API to browser or SPA clients:

```php
'billing' => [
    'api' => [
        'enabled' => true,
        'auth_middleware' => ['auth:sanctum'],
    ],
],
```

If Sanctum is not installed, the installer can offer to add it for you.

## Using an existing middleware

If your application already has a guard or middleware stack, use that instead:

```php
'billing' => [
    'api' => [
        'auth_middleware' => ['auth', 'verified'],
    ],
],
```

## Document builder mode

When `billing.database.enabled` is `false`, the API is disabled automatically.

That is intentional:

- there is no local data store to query
- invoice sharing links cannot be resolved from the database
- Scramble docs for the billing API are also disabled
- the fluent document builder still works for offline PDF generation

## Public invoice sharing

Public invoice sharing is separate from authenticated API access.

It uses a signed URL and should be treated as read-only distribution, not as a replacement for authentication.

## Security recommendation

For any production application:

1. Keep API routes behind an auth middleware.
2. Keep public share links signed and time-limited.
3. Do not expose internal routes without an explicit reason.
