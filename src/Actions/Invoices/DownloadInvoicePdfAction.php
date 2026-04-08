<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Proovit\Billing\Contracts\PdfRendererInterface;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Invoice;
use Symfony\Component\HttpFoundation\Response;

final class DownloadInvoicePdfAction
{
    public function __construct(private readonly PdfRendererInterface $renderer) {}

    public function handle(Invoice|InvoiceDocumentData $document, ?string $filename = null): Response
    {
        $document = $document instanceof Invoice
            ? InvoiceDocumentData::fromInvoice($document)
            : $document;

        return $this->renderer->download(
            $filename ?? sprintf(
                '%s-%s.pdf',
                strtolower($document->documentType instanceof InvoiceType ? $document->documentType->value : 'invoice'),
                $document->number ?? now()->format('YmdHis')
            ),
            'billing::pdf.invoice',
            $document->toViewModel(),
            [
                'paper' => config('billing.pdf.paper', 'a4'),
                'orientation' => config('billing.pdf.orientation', 'portrait'),
                'locale' => $document->locale ?? config('billing.companies.default_locale', 'fr'),
            ]
        );
    }
}
