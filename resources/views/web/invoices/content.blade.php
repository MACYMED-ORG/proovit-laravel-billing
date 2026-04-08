@php
    $invoice = $document['invoice'];
    $invoiceModel = $invoice_model ?? null;
    $locale = app()->getLocale();
    $currency = $invoice->currency ?: config('billing.invoice.default_currency', 'EUR');
    $documentType = $invoice->document_type?->label() ?? __('billing::web.document_title');
    $statusLabel = $invoice->status?->label() ?? __('billing::pdf.draft');
    $seller = $invoice->seller_snapshot ?? [];
    $customer = $invoice->customer_snapshot ?? [];
    $company = $invoice->company;
    $customerEntity = $invoice->customer;
    $lineItems = collect($invoice->lines ?? [])->sortBy('sort_order');
    $payments = collect($invoice->payments ?? []);
    $formatMoney = static fn ($amount) => number_format((float) $amount, 2, ',', ' ').' '.$currency;
    $formatDate = static fn ($date) => $date ? $date->locale($locale)->translatedFormat('d/m/Y') : '—';
    $formatDateTime = static fn ($date) => $date ? $date->locale($locale)->translatedFormat('d/m/Y H:i') : '—';
    $sellerAddress = data_get($seller, 'address') ?? data_get($seller, 'full_address');
    $customerAddress = data_get($customer, 'billing_address');
    $subtotalAmount = (float) $invoice->subtotal_amount;
    $taxAmount = (float) $invoice->tax_amount;
    $totalAmount = (float) $invoice->total_amount;
    $paidAmount = (float) $invoice->paid_amount;
    $balanceDue = (float) $invoice->balance_due;
@endphp

<main class="app-shell">
    <section class="hero">
        <div>
            <div class="eyebrow">{{ __('billing::web.preview_eyebrow') }}</div>
            <h1>{{ $documentType }} <span>{{ $invoice->number ?? __('billing::pdf.draft') }}</span></h1>
            <p>{{ __('billing::web.preview_description') }}</p>
        </div>
        <div class="hero-actions">
            @if (! $shared && $invoice->public_share_url)
                <a class="button secondary" href="{{ $invoice->public_share_url }}" target="_blank" rel="noopener">{{ __('billing::web.public_share') }}</a>
            @endif
            @if (! $shared)
                <a class="button" href="{{ route('billing.invoices.print', $invoiceModel ?? $invoice) }}" target="_blank" rel="noopener">{{ __('billing::web.print_view') }}</a>
            @endif
        </div>
    </section>

    <section class="status-grid">
        <article class="card">
            <span class="card-label">{{ __('billing::web.status') }}</span>
            <strong>{{ $statusLabel }}</strong>
            <small>{{ $invoice->status?->value ?? 'draft' }}</small>
        </article>
        <article class="card">
            <span class="card-label">{{ __('billing::web.issued_at') }}</span>
            <strong>{{ $formatDate($invoice->issued_at) }}</strong>
        </article>
        <article class="card">
            <span class="card-label">{{ __('billing::web.due_at') }}</span>
            <strong>{{ $formatDate($invoice->due_at) }}</strong>
        </article>
        <article class="card">
            <span class="card-label">{{ __('billing::web.balance_due') }}</span>
            <strong>{{ $formatMoney($balanceDue) }}</strong>
        </article>
    </section>

    <section class="content-grid">
        <article class="panel">
            <div class="panel-title">{{ __('billing::web.seller') }}</div>
            <h2>{{ data_get($seller, 'legal_name') ?? $company?->legal_name ?? data_get($seller, 'display_name') ?? $company?->display_name }}</h2>
            @if (data_get($seller, 'display_name') && data_get($seller, 'display_name') !== data_get($seller, 'legal_name'))
                <p class="muted">{{ data_get($seller, 'display_name') }}</p>
            @endif
            <p class="muted">{!! nl2br(e(implode("\n", array_filter([
                data_get($sellerAddress, 'line1'),
                data_get($sellerAddress, 'line2'),
                trim(implode(' ', array_filter([data_get($sellerAddress, 'postal_code'), data_get($sellerAddress, 'city')]))),
                data_get($sellerAddress, 'region'),
                data_get($sellerAddress, 'country'),
            ])))) !!}</p>
            <p class="muted">{{ data_get($seller, 'contact_email') ?? $company?->email }}</p>
            <p class="muted">{{ data_get($seller, 'contact_phone') ?? $company?->phone }}</p>
        </article>

        <article class="panel">
            <div class="panel-title">{{ __('billing::web.customer') }}</div>
            <h2>{{ data_get($customer, 'legal_name') ?? $customerEntity?->legal_name ?? data_get($customer, 'full_name') ?? $customerEntity?->full_name }}</h2>
            @if (data_get($customer, 'full_name') && data_get($customer, 'full_name') !== data_get($customer, 'legal_name'))
                <p class="muted">{{ data_get($customer, 'full_name') }}</p>
            @endif
            <p class="muted">{{ data_get($customer, 'reference') ?? $customerEntity?->reference }}</p>
            <p class="muted">{!! nl2br(e(implode("\n", array_filter([
                data_get($customerAddress, 'line1'),
                data_get($customerAddress, 'line2'),
                trim(implode(' ', array_filter([data_get($customerAddress, 'postal_code'), data_get($customerAddress, 'city')]))),
                data_get($customerAddress, 'region'),
                data_get($customerAddress, 'country'),
            ])))) !!}</p>
            <p class="muted">{{ data_get($customer, 'email') ?? $customerEntity?->email }}</p>
            <p class="muted">{{ data_get($customer, 'phone') ?? $customerEntity?->phone }}</p>
        </article>
    </section>

    <section class="panel">
        <div class="panel-title">{{ __('billing::web.lines') }}</div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('billing::web.description') }}</th>
                        <th>{{ __('billing::web.quantity') }}</th>
                        <th>{{ __('billing::web.unit_price') }}</th>
                        <th>{{ __('billing::web.tax_rate') }}</th>
                        <th>{{ __('billing::web.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lineItems as $line)
                        <tr>
                            <td>{{ $line->sort_order ?? '—' }}</td>
                            <td>
                                <strong>{{ $line->description }}</strong>
                                @if ($line->product?->name)
                                    <div class="muted">{{ $line->product->name }}@if($line->product->sku) · {{ $line->product->sku }}@endif</div>
                                @endif
                            </td>
                            <td>{{ $line->quantity ?? '—' }}</td>
                            <td>{{ $formatMoney($line->unit_price ?? 0) }}</td>
                            <td>{{ $line->tax_rate !== null ? $line->tax_rate.'%' : '—' }}</td>
                            <td>{{ $formatMoney($line->total_amount ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">{{ __('billing::web.no_lines') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="totals-grid">
        <article class="panel">
            <div class="panel-title">{{ __('billing::web.totals') }}</div>
            <dl class="totals-list">
                <div><dt>{{ __('billing::web.subtotal') }}</dt><dd>{{ $formatMoney($subtotalAmount) }}</dd></div>
                <div><dt>{{ __('billing::web.tax_total') }}</dt><dd>{{ $formatMoney($taxAmount) }}</dd></div>
                <div><dt>{{ __('billing::web.total') }}</dt><dd>{{ $formatMoney($totalAmount) }}</dd></div>
                <div><dt>{{ __('billing::web.paid_amount') }}</dt><dd>{{ $formatMoney($paidAmount) }}</dd></div>
                <div><dt>{{ __('billing::web.balance_due') }}</dt><dd>{{ $formatMoney($balanceDue) }}</dd></div>
            </dl>
        </article>

        <article class="panel">
            <div class="panel-title">{{ __('billing::web.payments') }}</div>
            @if ($payments->isEmpty())
                <p class="muted">{{ __('billing::web.no_payments') }}</p>
            @else
                <ul class="event-list">
                    @foreach ($payments as $payment)
                        <li>
                            <strong>{{ $formatMoney($payment->amount ?? 0) }}</strong>
                            <span>{{ $payment->method?->label() ?? $payment->method?->value ?? '—' }}</span>
                            <small>{{ $formatDateTime($payment->paid_at) }}</small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>
    </section>

    <section class="panel">
        <div class="panel-title">{{ __('billing::web.notes') }}</div>
        <p class="muted">{{ $invoice->notes ?: __('billing::web.no_notes') }}</p>
    </section>
</main>
