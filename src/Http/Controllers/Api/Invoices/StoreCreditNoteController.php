<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\CreateCreditNoteFromInvoiceAction;
use Proovit\Billing\Http\Resources\Api\Invoices\CreditNoteResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices', description: 'Manage invoices, payments, credit notes, and public share links.')]
final class StoreCreditNoteController extends Controller
{
    #[Endpoint(
        operationId: 'storeCreditNote',
        title: 'Create credit note',
        description: 'Create a credit note linked to the selected invoice.'
    )]
    #[Response(status: 201, type: 'Proovit\Billing\Http\Resources\Api\Invoices\CreditNoteResource', description: 'Created credit note with its persisted line items.')]
    public function __invoke(Invoice $invoice, CreateCreditNoteFromInvoiceAction $createCreditNote): JsonResponse
    {
        $creditNote = $createCreditNote->handle($invoice->loadMissing(['lines']));

        return (new CreditNoteResource($creditNote->loadMissing(['lines'])))->response()->setStatusCode(201);
    }
}
