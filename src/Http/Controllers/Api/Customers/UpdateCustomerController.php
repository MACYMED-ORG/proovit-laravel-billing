<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Customers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Customers\UpdateCustomerAction;
use Proovit\Billing\Http\Requests\Api\Customers\UpdateCustomerRequest;
use Proovit\Billing\Http\Resources\Api\Customers\CustomerResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

#[Group('Customers', description: 'List, create, view, update, and delete billing customers and their related addresses.')]
final class UpdateCustomerController extends Controller
{
    #[Endpoint(
        operationId: 'updateCustomer',
        title: 'Update customer',
        description: 'Update a billing customer and refresh its related context.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Customers\CustomerResource', description: 'Updated customer with refreshed company context and related addresses.')]
    public function __invoke(UpdateCustomerRequest $request, Customer $customer, UpdateCustomerAction $updateCustomerAction): CustomerResource
    {
        $payload = $request->validated();
        $companyId = $this->resolveCompanyId($payload);

        return new CustomerResource($updateCustomerAction->handle($customer, $payload, $companyId));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveCompanyId(array $payload): ?int
    {
        if (isset($payload['company_uuid_identifier'])) {
            return Company::query()->where('uuid_identifier', $payload['company_uuid_identifier'])->firstOrFail()->id;
        }

        return isset($payload['company_id']) ? (int) $payload['company_id'] : null;
    }
}
