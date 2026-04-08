<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Customers;

use Proovit\Billing\Models\Customer;

final class ShowCustomerAction
{
    public function handle(Customer $customer): Customer
    {
        return $customer->loadMissing(['company', 'addresses', 'invoices']);
    }
}
