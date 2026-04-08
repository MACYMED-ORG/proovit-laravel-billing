<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Customers;

use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

final class UpdateCustomerAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Customer $customer, array $data, ?int $companyId = null): Customer
    {
        if (isset($data['company_uuid_identifier'])) {
            $companyId = Company::query()->where('uuid_identifier', $data['company_uuid_identifier'])->firstOrFail()->id;
        } elseif (isset($data['company_id'])) {
            $companyId = (int) $data['company_id'];
        }

        unset($data['company_id'], $data['company_uuid_identifier']);

        if ($companyId !== null) {
            $data['company_id'] = $companyId;
        }

        $customer->update($data);

        return $customer->refresh();
    }
}
