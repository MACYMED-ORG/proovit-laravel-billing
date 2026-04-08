<section class="hero">
    <div>
        <div class="eyebrow">{{ __('billing::pdf.document_title') }}</div>
        <h1>{{ $documentType }} <span>{{ $invoice->number ?? __('billing::pdf.draft') }}</span></h1>
        <p class="subtitle">
            {{ collect([
                $companySnapshot['display_name'] ?? null,
                $companySnapshot['legal_name'] ?? $company?->legal_name ?? null,
            ])->filter()->implode(' · ') ?: __('billing::pdf.seller') }}
            @if ($establishment)
                · {{ $establishment->name ?? $establishment->label ?? __('billing::pdf.seller') }}
            @endif
        </p>
    </div>

    <div class="meta-grid">
        <div class="meta-card">
            <div class="meta-label">{{ __('billing::pdf.status') }}</div>
            <div class="meta-value">{{ $statusLabel }}</div>
        </div>
        <div class="meta-card">
            <div class="meta-label">{{ __('billing::pdf.type') }}</div>
            <div class="meta-value">{{ $documentType }}</div>
        </div>
        <div class="meta-card">
            <div class="meta-label">{{ __('billing::pdf.issued_on') }}</div>
            <div class="meta-value">{{ $formatDate($invoice->issued_at) }}</div>
        </div>
        <div class="meta-card">
            <div class="meta-label">{{ __('billing::pdf.due_on') }}</div>
            <div class="meta-value">{{ $formatDate($invoice->due_at) }}</div>
        </div>
    </div>
</section>
