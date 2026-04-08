<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\DeleteCustomerAction;
use Proovit\Billing\Models\Customer;

#[Group('Customers')]
final class DeleteCustomerController extends Controller
{
    public function __invoke(Customer $customer, DeleteCustomerAction $deleteCustomerAction): Response
    {
        $deleteCustomerAction->handle($customer);

        return response()->noContent();
    }
}
