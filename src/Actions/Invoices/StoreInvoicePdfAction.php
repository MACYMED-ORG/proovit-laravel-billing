<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\Storage;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\Invoice;

final class StoreInvoicePdfAction
{
    public function __construct(private readonly GenerateInvoicePdfAction $generateInvoicePdfAction) {}

    public function handle(Invoice|InvoiceDocumentBuilder|InvoiceDocumentData $document, ?string $filename = null): string
    {
        $document = $document instanceof Invoice
            ? InvoiceDocumentData::fromInvoice($document)
            : ($document instanceof InvoiceDocumentBuilder
                ? $document->build()
                : $document);

        $disk = (string) (config('billing.documents.disk') ?? config('billing.pdf.disk') ?? 'public');
        $directory = trim((string) config('billing.documents.invoices', config('billing.pdf.directory', 'billing/invoices')), '/');
        $fileName = $filename ?? sprintf(
            '%s-%s.pdf',
            strtolower($document->documentType instanceof InvoiceType ? $document->documentType->value : 'invoice'),
            $document->number ?? now()->format('YmdHis')
        );
        $path = $directory === '' ? $fileName : $directory.'/'.$fileName;

        Storage::disk($disk)->put($path, $this->generateInvoicePdfAction->handle($document));

        return $path;
    }
}
