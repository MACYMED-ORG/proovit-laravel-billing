<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\ShowCustomerAction;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Models\Customer;

#[Group('Customers')]
final class ShowCustomerController extends Controller
{
    public function __invoke(Customer $customer, ShowCustomerAction $showCustomerAction): CustomerResource
    {
        return new CustomerResource($showCustomerAction->handle($customer));
    }
}
