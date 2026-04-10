<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\CreateDraftInvoiceAction;
use Proovit\Billing\Http\Requests\Api\Invoices\StoreInvoiceRequest;
use Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Support\InvoiceDraftPayloadMapper;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class StoreInvoiceController extends Controller
{
    #[Endpoint(
        operationId: 'storeInvoice',
        title: 'Create draft invoice',
        description: 'Create a draft invoice with customer, lines, totals, and persisted snapshots.'
    )]
    #[Response(status: 201, type: 'Proovit\Billing\Http\Resources\Api\Invoices\InvoiceResource', description: 'Created draft invoice with seller and customer snapshots, lines, totals, and related metadata.')]
    public function __invoke(StoreInvoiceRequest $request, CreateDraftInvoiceAction $createDraftInvoice, InvoiceDraftPayloadMapper $mapper): JsonResponse
    {
        $payload = $request->validated();
        $companyId = $this->resolveCompanyId($payload);
        $customerId = $this->resolveCustomerId($payload);

        $invoice = $createDraftInvoice->handle(
            $mapper->map($payload),
            $companyId,
            $customerId,
        );

        return (new InvoiceResource($invoice->loadMissing(['company', 'customer', 'series', 'reservation', 'quote', 'lines', 'payments'])))->response()->setStatusCode(201);
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
