@php
    /** @var \Illuminate\Support\Fluent|\Proovit\Billing\Models\Invoice $invoice */

    $locale = app()->getLocale();
    $currency = $invoice->currency ?: config('billing.invoice.default_currency', 'EUR');
    $documentType = $invoice->document_type?->label() ?? __('billing::pdf.document_title');
    $statusLabel = $invoice->status?->label() ?? __('billing::pdf.draft');
    $company = $invoice->company;
    $customer = $invoice->customer;
    $companySnapshot = $invoice->seller_snapshot ?? ($company?->toSnapshot()->toArray() ?? []);
    $customerSnapshot = $invoice->customer_snapshot ?? ($customer?->toSnapshot()->toArray() ?? []);
    $payments = $invoice->payments ?? collect();
    $paidTotal = $payments->sum(fn ($payment) => (float) $payment->amount);
    $balanceDue = max(0, (float) $invoice->total_amount - (float) $paidTotal);
    $bankAccount = $company?->defaultBankAccount;
    $establishment = $invoice->establishment ?? $company?->defaultEstablishment;

    $formatMoney = static fn ($amount) => number_format((float) $amount, 2, ',', ' ') . ' ' . $currency;
    $formatDate = static fn ($date) => $date ? $date->locale($locale)->translatedFormat('d/m/Y') : '—';
    $formatDateTime = static fn ($date) => $date ? $date->locale($locale)->translatedFormat('d/m/Y H:i') : '—';
    $formatAddress = static function (?array $address): string {
        if (! is_array($address)) {
            return '—';
        }

        $parts = array_filter([
            $address['line1'] ?? null,
            $address['line2'] ?? null,
            trim(implode(' ', array_filter([
                $address['postal_code'] ?? null,
                $address['city'] ?? null,
            ]))),
            $address['region'] ?? null,
            $address['country'] ?? null,
        ]);

        return $parts === [] ? '—' : implode("\n", $parts);
    };
    $formatContact = static function (?array $contact): string {
        if (! is_array($contact)) {
            return '—';
        }

        $parts = array_filter([
            $contact['name'] ?? null,
            $contact['email'] ?? null,
            $contact['phone'] ?? null,
        ]);

        return $parts === [] ? '—' : implode("\n", $parts);
    };
    $companyAddress = data_get($companySnapshot, 'address') ?? data_get($companySnapshot, 'full_address');
    $customerAddress = data_get($customerSnapshot, 'billing_address');
    $legalMentions = $companySnapshot['legal_mentions'] ?? [];
    $lineItems = $invoice->lines->sortBy('sort_order');
    $subtotalAmount = (float) $invoice->subtotal_amount;
    $taxAmount = (float) $invoice->tax_amount;
    $totalAmount = (float) $invoice->total_amount;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', $locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('billing::pdf.title', ['type' => $documentType, 'number' => $invoice->number ?? __('billing::pdf.draft')]) }}</title>
    @include('billing::pdf.partials.styles')
</head>
<body>
<main class="page">
    @include('billing::pdf.partials.header')

    <section class="stack">
        @include('billing::pdf.partials.summary')

        @include('billing::pdf.partials.parties')

        @include('billing::pdf.partials.lines')

        @include('billing::pdf.partials.payments')

        @include('billing::pdf.partials.notes-legal')

        @include('billing::pdf.partials.footer')
    </section>
</main>
</body>
</html>
