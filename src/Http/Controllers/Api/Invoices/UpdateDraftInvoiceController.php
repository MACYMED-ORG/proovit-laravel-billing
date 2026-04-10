<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\UpdateDraftInvoiceAction;
use Proovit\Billing\Http\Requests\Api\Invoices\UpdateDraftInvoiceRequest;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Support\InvoiceDraftPayloadMapper;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class UpdateDraftInvoiceController extends Controller
{
    #[Endpoint(
        operationId: 'updateDraftInvoice',
        title: 'Update draft invoice',
        description: 'Update a draft invoice before finalization and refresh all calculated totals.'
    )]
    #[Response(type: 'Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource', description: 'Updated draft invoice with recalculated totals, refreshed relations, and snapshot data.')]
    public function __invoke(UpdateDraftInvoiceRequest $request, Invoice $invoice, UpdateDraftInvoiceAction $updateDraftInvoiceAction, InvoiceDraftPayloadMapper $mapper): InvoiceResource
    {
        $payload = $request->validated();
        $companyId = $this->resolveCompanyId($payload);
        $customerId = $this->resolveCustomerId($payload);

        return new InvoiceResource(
            $updateDraftInvoiceAction->handle(
                $invoice,
                $mapper->map($payload),
                $companyId,
                $customerId,
            )->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments.invoice', 'payments.allocations'])
        );
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

    /**
     * @param  array<string, mixed>  $payload
     */
    private function resolveCustomerId(array $payload): ?int
    {
        if (isset($payload['customer_uuid_identifier'])) {
            return Customer::query()->where('uuid_identifier', $payload['customer_uuid_identifier'])->firstOrFail()->id;
        }

        return isset($payload['customer_id']) ? (int) $payload['customer_id'] : null;
    }
}
