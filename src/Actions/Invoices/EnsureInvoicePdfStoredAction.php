<?php

declare(strict_types=1);

namespace Proovit\Billing\Actions\Invoices;

use Illuminate\Support\Facades\Storage;
use Proovit\Billing\Enums\DocumentRenderType;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Models\DocumentRender;
use Proovit\Billing\Models\Invoice;

final class EnsureInvoicePdfStoredAction
{
    public function __construct(
        private readonly StoreInvoicePdfAction $storeInvoicePdfAction,
    ) {}

    public function handle(Invoice $invoice, ?string $filename = null): DocumentRender
    {
        $existingRender = $invoice->latestPdfDocumentRender();
        $disk = (string) (config('billing.documents.disk') ?? config('billing.pdf.disk') ?? 'public');

        if ($existingRender instanceof DocumentRender && $existingRender->getAttribute('path') && Storage::disk($disk)->exists((string) $existingRender->getAttribute('path'))) {
            return $existingRender;
        }

        $resolvedFilename = $filename
            ?? (($existingRender?->getAttribute('path')) ? basename((string) $existingRender->getAttribute('path')) : null)
            ?? sprintf(
                '%s-%s.pdf',
                strtolower(($invoice->getAttribute('document_type') instanceof InvoiceType ? $invoice->getAttribute('document_type')->value : 'invoice')),
                $invoice->getAttribute('number') ?? now()->format('YmdHis')
            );

        $path = $this->storeInvoicePdfAction->handle($invoice, $resolvedFilename);

        $render = DocumentRender::create([
            'company_id' => $invoice->getAttribute('company_id'),
            'invoice_id' => $invoice->id,
            'document_type' => $invoice->getAttribute('document_type') instanceof InvoiceType ? $invoice->getAttribute('document_type')->value : InvoiceType::Invoice->value,
            'render_type' => DocumentRenderType::Pdf->value,
            'disk' => $disk,
            'path' => $path,
            'mime_type' => 'application/pdf',
            'size_bytes' => Storage::disk($disk)->exists($path) ? Storage::disk($disk)->size($path) : null,
        ]);

        return $render;
    }
}
