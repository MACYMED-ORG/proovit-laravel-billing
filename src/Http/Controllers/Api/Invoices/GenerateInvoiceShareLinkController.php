<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\Invoices;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Proovit\Billing\Actions\Invoices\GenerateInvoiceShareLinkAction;
use Proovit\Billing\Models\Invoice;

#[Group('Invoices')]
final class GenerateInvoiceShareLinkController extends Controller
{
    public function __invoke(Invoice $invoice, GenerateInvoiceShareLinkAction $generateInvoiceShareLinkAction): JsonResponse
    {
        $url = $generateInvoiceShareLinkAction->handle($invoice);

        return response()->json([
            'data' => [
                'invoice_uuid_identifier' => $invoice->uuid_identifier,
                'public_share_url' => $url,
                'public_share_token' => $invoice->public_share_token,
                'public_share_expires_at' => $invoice->public_share_expires_at?->toIso8601String(),
            ],
        ]);
    }
}
