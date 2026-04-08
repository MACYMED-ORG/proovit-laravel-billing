# Demo data

The package ships with factories and a demo seeder for realistic sample data.

## Factory classes

Package factories live in `database/factories` and follow the Eloquent factory naming convention.

Examples:

```php
$company = \Proovit\Billing\Models\Company::factory()->create();
$customer = \Proovit\Billing\Models\Customer::factory()->for($company)->create();
$product = \Proovit\Billing\Models\Product::factory()->for($company)->create();
```

## Demo seeder

The `BillingDemoSeeder` creates:

- a company
- a default establishment
- a default bank account
- a tax rate
- a customer
- a quote with line items
- a converted invoice
- a payment and allocation

Run it in an application:

```php
app(\Proovit\Billing\Database\Seeders\BillingDemoSeeder::class)->run();
```

## Why it matters

- faster feature testing
- realistic sandbox data
- quicker UI and API demos
