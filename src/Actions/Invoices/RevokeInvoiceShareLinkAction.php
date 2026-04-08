<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Proovit\Billing\Models\Invoice;

final class RevokeInvoiceShareLinkAction
{
    public function handle(Invoice $invoice): Invoice
    {
        $invoice->forceFill([
            'public_share_token' => null,
            'public_shared_at' => null,
            'public_share_expires_at' => null,
        ])->save();

        return $invoice->refresh();
    }
}
