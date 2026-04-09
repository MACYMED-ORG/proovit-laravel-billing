<main class="app-shell">
    <section class="hero">
        <div>
            <div class="eyebrow">{{ __('billing::web.preview_eyebrow') }}</div>
            <h1>{{ __('billing::pdf.document_title') }} <span>{{ $document['invoice']->number ?? __('billing::pdf.draft') }}</span></h1>
            <p>{{ __('billing::web.preview_description') }}</p>
        </div>
        <div class="hero-actions">
            @if (! $shared && ($document['invoice']->public_share_url ?? null))
                <a class="button secondary" href="{{ $document['invoice']->public_share_url }}" target="_blank" rel="noopener">{{ __('billing::web.public_share') }}</a>
            @endif
            @if (! $shared && $invoice_model)
                <a class="button" href="{{ route('billing.invoices.print', $invoice_model) }}" target="_blank" rel="noopener">{{ __('billing::web.print_view') }}</a>
            @endif
        </div>
    </section>

    @include('billing::shared.invoices.document')
</main>
