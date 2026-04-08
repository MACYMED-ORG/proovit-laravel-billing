<table class="cards-2" style="margin-top: 14px;">
    <tr>
        <td style="width: 50%; padding-right: 10px; vertical-align: top;">
            <section class="panel">
                <h2>{{ __('billing::pdf.notes') }}</h2>
                @if (filled($invoice->notes))
                    <div class="value">{{ $invoice->notes }}</div>
                @else
                    <div class="empty-state">{{ __('billing::pdf.no_notes') }}</div>
                @endif
            </section>
        </td>
        <td style="width: 50%; vertical-align: top;">
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
                <table class="badge-grid" style="margin-top: 14px; width: 100%;">
                    <tr>
                        <td style="padding-right: 8px;"><span class="badge">{{ $statusLabel }}</span></td>
                        <td style="padding-right: 8px;"><span class="badge">{{ $documentType }}</span></td>
                        <td><span class="badge">{{ $currency }}</span></td>
                    </tr>
                </table>
            </section>
        </td>
    </tr>
</table>
