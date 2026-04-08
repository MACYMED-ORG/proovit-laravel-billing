<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Documents;

use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

final class SnapshotDocumentPartiesAction
{
    /**
     * @return array<string, mixed>
     */
    public function companySnapshot(Company $company): array
    {
        return array_merge(
            $company->toSnapshot()->toArray(),
            [
                'bank_details_optional_but_supported' => true,
            ]
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function customerSnapshot(Customer $customer): array
    {
        return $customer->toSnapshot()->toArray();
    }
}
