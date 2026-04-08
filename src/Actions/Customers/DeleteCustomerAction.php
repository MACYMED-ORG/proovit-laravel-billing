<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Customers;

use Proovit\Billing\Models\Customer;

final class DeleteCustomerAction
{
    public function handle(Customer $customer): void
    {
        $customer->delete();
    }
}
