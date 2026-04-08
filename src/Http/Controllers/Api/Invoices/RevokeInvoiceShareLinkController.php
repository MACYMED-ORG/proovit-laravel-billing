<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\RevokeInvoiceShareLinkAction;
use Proovit\Billing\Http\Requests\Api\Invoices\RevokeInvoiceShareLinkRequest;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class RevokeInvoiceShareLinkController extends Controller
{
    public function __invoke(
        RevokeInvoiceShareLinkRequest $request,
        Invoice $invoice,
        RevokeInvoiceShareLinkAction $revokeInvoiceShareLinkAction
    ): JsonResponse {
        $invoice = $revokeInvoiceShareLinkAction->handle($invoice);

        return response()->json([
            'data' => [
                'invoice_uuid_identifier' => $invoice->uuid_identifier,
                'public_share_url' => null,
                'public_share_token' => null,
                'public_share_expires_at' => null,
            ],
        ]);
    }
}
