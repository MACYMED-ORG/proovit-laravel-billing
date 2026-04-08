<table class="notes-grid">
    <tr>
        <td class="notes-cell">
            <section class="notes-box">
                <h2 class="box-title">{{ __('billing::pdf.notes') }}</h2>
                @if (filled($invoice->notes))
                    <div class="value">{{ $invoice->notes }}</div>
                @else
                    <div class="empty-state">{{ __('billing::pdf.no_notes') }}</div>
                @endif
            </section>
        </td>
        <td class="notes-cell">
            <section class="notes-box">
                <h2 class="box-title">{{ __('billing::pdf.legal_mentions_title') }}</h2>
                @if ($legalMentions)
                    <ul class="legal-list">
                        @foreach ($legalMentions as $mention)
                            <li>{{ $mention }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">{{ __('billing::pdf.no_legal_mentions') }}</div>
                @endif
                <div class="chips">
                    <span class="chip">{{ $statusLabel }}</span>
                    <span class="chip">{{ $documentType }}</span>
                    <span class="chip">{{ $currency }}</span>
                </div>
            </section>
        </td>
    </tr>
</table>
