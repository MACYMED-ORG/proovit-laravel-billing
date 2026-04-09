# Release Process

This package uses a simple release workflow:

1. Development happens on the `dev` branch.
2. Stable releases are merged into `main`.
3. Tags are created only from `main`.
4. `proovit/laravel-billing` and `proovit/filament-billing` should share the same release tag whenever they are released together.
5. If only the Filament plugin changes and the billing core does not, increment the fourth numeric segment on the plugin tag, for example `1.0.0.1`, `1.0.0.2`, and so on.

Release rules:

- Do not commit a `version` field to `composer.json`.
- Keep `dev` packages on explicit `dev-dev@dev` requirements in local sandboxes.
- For public releases, tag `main` and push the tag to GitHub and Packagist.

When in doubt, prefer a new tag over rewriting an existing one.
