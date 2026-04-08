<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\CreateCustomerAction;
use Proovit\Billing\Http\Requests\Api\Customers\StoreCustomerRequest;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;

#[Group('Customers')]
final class StoreCustomerController extends Controller
{
    public function __invoke(StoreCustomerRequest $request, CreateCustomerAction $createCustomerAction): JsonResponse
    {
        $customer = $createCustomerAction->handle($request->validated());

        return (new CustomerResource($customer->loadMissing(['company', 'addresses', 'invoices'])))->response()->setStatusCode(201);
    }
}
