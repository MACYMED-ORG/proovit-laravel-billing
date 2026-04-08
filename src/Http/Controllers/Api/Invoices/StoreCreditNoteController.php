<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\CreateCreditNoteFromInvoiceAction;
use Proovit\Billing\Http\Resources\Api\Invoices\CreditNoteResource;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class StoreCreditNoteController extends Controller
{
    public function __invoke(Invoice $invoice, CreateCreditNoteFromInvoiceAction $createCreditNote): JsonResponse
    {
        $creditNote = $createCreditNote->handle($invoice->loadMissing(['lines']));

        return (new CreditNoteResource($creditNote->loadMissing(['lines'])))->response()->setStatusCode(201);
    }
}
