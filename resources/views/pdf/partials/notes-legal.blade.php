<div class="cards-2">
    <section class="panel">
        <h2>{{ __('billing::pdf.notes') }}</h2>
        @if (filled($invoice->notes))
            <div class="value">{{ $invoice->notes }}</div>
        @else
            <div class="empty-state">{{ __('billing::pdf.no_notes') }}</div>
        @endif
    </section>

    <section class="panel">
        <h2>{{ __('billing::pdf.legal_mentions_title') }}</h2>
        @if ($legalMentions)
            <ul class="legal-list">
                @foreach ($legalMentions as $mention)
                    <li>{{ $mention }}</li>
                @endforeach
            </ul>
        @else
            <div class="empty-state">{{ __('billing::pdf.no_legal_mentions') }}</div>
        @endif
        <div class="badge-grid" style="margin-top: 14px;">
            <span class="badge">{{ $statusLabel }}</span>
            <span class="badge">{{ $documentType }}</span>
            <span class="badge">{{ $currency }}</span>
        </div>
    </section>
</div>
