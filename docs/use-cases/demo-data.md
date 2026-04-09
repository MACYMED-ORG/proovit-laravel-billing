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

- a company with billing defaults
- two establishments
- two bank accounts
- two tax rates
- two customers with billing/shipping addresses
- three products with prices
- three invoice series, one for each document type
- a quote with multiple line items
- a converted invoice and public share link
- a draft invoice
- a payment and allocation
- a credit note from the converted invoice
- a reminder
- a document render record
- a Factur-X export record
- an audit log entry

Run it in an application:

```php
app(\Proovit\Billing\Database\Seeders\BillingDemoSeeder::class)->run();
```

If you want the package demo data in your application seeders, add:

```php
use Proovit\Billing\Database\Seeders\BillingDemoSeeder;

public function run(): void
{
    $this->call(BillingDemoSeeder::class);
}
```

## Why it matters

- faster feature testing
- realistic sandbox data
- quicker UI and API demos
