<?php

declare(strict_types=1);

use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Invoice;

it('recognizes editable draft invoices', function (): void {
    $invoice = new Invoice([
        'document_type' => InvoiceType::Invoice,
        'status' => InvoiceStatus::Draft,
    ]);

    expect($invoice->isEditableDraft())->toBeTrue();
});

it('rejects finalized invoices as editable drafts', function (): void {
    $invoice = new Invoice([
        'document_type' => InvoiceType::Invoice,
        'status' => InvoiceStatus::Finalized,
    ]);

    expect($invoice->isEditableDraft())->toBeFalse();
});

it('rejects non invoice documents as editable drafts', function (): void {
    $quote = new Invoice([
        'document_type' => InvoiceType::Quote,
        'status' => InvoiceStatus::Draft,
    ]);

    expect($quote->isEditableDraft())->toBeFalse();
});
