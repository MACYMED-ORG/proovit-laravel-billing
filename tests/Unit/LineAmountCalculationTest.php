<?php

declare(strict_types=1);

use Proovit\Billing\Enums\CreditNoteStatus;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\QuoteStatus;
use Proovit\Billing\Models\CreditNote;
use Proovit\Billing\Models\CreditNoteLine;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceLine;
use Proovit\Billing\Models\Quote;

it('recalculates invoice line amounts on save preparation', function (): void {
    $line = new InvoiceLine([
        'quantity' => '2',
        'unit_price' => '50.00',
        'discount_amount' => '10.00',
        'tax_rate' => '20.00',
    ]);

    $line->syncCalculatedAmounts();

    expect($line->subtotal_amount)->toBe('90.00')
        ->and($line->tax_amount)->toBe('18.00')
        ->and($line->total_amount)->toBe('108.00');
});

it('treats quotes and credit notes as editable only in draft-like states', function (): void {
    $quote = new Quote(['status' => QuoteStatus::Draft]);
    $acceptedQuote = new Quote(['status' => QuoteStatus::Accepted]);
    $creditNote = new CreditNote(['status' => CreditNoteStatus::Draft]);
    $finalizedCreditNote = new CreditNote(['status' => CreditNoteStatus::Finalized]);
    $invoice = new Invoice(['document_type' => InvoiceType::Invoice, 'status' => InvoiceStatus::Draft]);
    $finalizedInvoice = new Invoice(['document_type' => InvoiceType::Invoice, 'status' => InvoiceStatus::Finalized]);

    expect($quote->canManageLineItems())->toBeTrue()
        ->and($acceptedQuote->canManageLineItems())->toBeFalse()
        ->and($creditNote->canManageLineItems())->toBeTrue()
        ->and($finalizedCreditNote->canManageLineItems())->toBeFalse()
        ->and($invoice->canManageLineItems())->toBeTrue()
        ->and($finalizedInvoice->canManageLineItems())->toBeFalse();
});
