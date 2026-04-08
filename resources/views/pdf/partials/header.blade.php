<section class="hero">
    <table class="header-grid">
        <tr>
            <td class="header-brand">
                <div class="brand-row">
                    <div class="brand-mark" aria-hidden="true">
                        <span class="brand-mark-inner"></span>
                    </div>
                    <div class="brand-copy">
                        <div class="brand-name">{{ $companySnapshot['display_name'] ?? $companySnapshot['legal_name'] ?? $company?->legal_name ?? __('billing::pdf.seller') }}</div>
                        <div class="brand-subtitle">{{ $companySnapshot['legal_form'] ?? $company?->legal_form ?? __('billing::pdf.document_title') }}</div>
                    </div>
                </div>
            </td>
            <td class="header-title">
                <div class="document-title">{{ $documentType }}</div>
                <div class="document-number">{{ $invoice->number ?? __('billing::pdf.draft') }}</div>
            </td>
        </tr>
    </table>

    <table class="invoice-meta">
        <tr>
            <td>
                <div class="meta-label">{{ __('billing::pdf.issued_on') }}</div>
                <div class="meta-value">{{ $formatDate($invoice->issued_at) }}</div>
            </td>
            <td>
                <div class="meta-label">{{ __('billing::pdf.due_on') }}</div>
                <div class="meta-value">{{ $formatDate($invoice->due_at) }}</div>
            </td>
            <td>
                <div class="meta-label">{{ __('billing::pdf.status') }}</div>
                <div class="meta-value">{{ $statusLabel }}</div>
            </td>
            <td>
                <div class="meta-label">{{ __('billing::pdf.type') }}</div>
                <div class="meta-value">{{ $documentType }}</div>
            </td>
        </tr>
    </table>
</section>
