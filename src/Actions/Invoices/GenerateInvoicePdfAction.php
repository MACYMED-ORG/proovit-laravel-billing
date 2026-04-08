<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;
use Proovit\Billing\Contracts\PdfRendererInterface;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Models\Invoice;

final class GenerateInvoicePdfAction
{
    public function __construct(private readonly PdfRendererInterface $renderer) {}

    public function handle(Invoice|InvoiceDocumentBuilder|InvoiceDocumentData $document): string
    {
        $document = $document instanceof Invoice
            ? InvoiceDocumentData::fromInvoice($document)
            : ($document instanceof InvoiceDocumentBuilder
                ? $document->build()
                : $document);

        return $this->renderer->render('billing::pdf.invoice', $document->toViewModel(), [
            'paper' => config('billing.pdf.paper', 'a4'),
            'orientation' => config('billing.pdf.orientation', 'portrait'),
            'locale' => $document->locale ?? config('billing.companies.default_locale', 'fr'),
        ]);
    }
}
