<?php

declare(strict_types=1);

namespace Proovit\Billing\DTOs\Documents;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceTotalsData;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyBankAccount;
use Proovit\Billing\Models\CompanyEstablishment;
use Proovit\Billing\Models\Customer;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\InvoiceLine;
use Proovit\Billing\Models\InvoiceNumberReservation;
use Proovit\Billing\Models\InvoiceSeries;
use Proovit\Billing\Models\Payment;
use Proovit\Billing\Models\Quote;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\Money;

final readonly class InvoiceDocumentData
{
    /**
     * @param  array<int, array<string, mixed>>  $lines
     * @param  array<int, array<string, mixed>>|null  $payments
     * @param  array<string, mixed>|null  $series
     * @param  array<string, mixed>|null  $reservation
     * @param  array<string, mixed>|null  $bankAccount
     * @param  array<string, mixed>|null  $establishment
     * @param  array<string, mixed>|null  $quote
     * @param  array<int, string>|null  $legalMentions
     */
    public function __construct(
        public CompanyIdentitySnapshot $seller,
        public CustomerIdentitySnapshot $customer,
        public array $lines,
        public string $currency = 'EUR',
        public ?InvoiceType $documentType = InvoiceType::Invoice,
        public ?InvoiceStatus $status = null,
        public ?string $number = null,
        public DateTimeInterface|string|null $issuedAt = null,
        public DateTimeInterface|string|null $dueAt = null,
        public ?array $series = null,
        public ?array $reservation = null,
        public ?array $bankAccount = null,
        public ?array $establishment = null,
        public ?array $payments = null,
        public ?array $quote = null,
        public ?string $notes = null,
        public ?array $legalMentions = null,
        public ?Money $subtotal = null,
        public ?Money $taxTotal = null,
        public ?Money $total = null,
        public ?Money $paidTotal = null,
        public ?Money $balanceDue = null,
        public ?string $locale = null,
        public ?string $publicShareUrl = null,
    ) {}

    public static function fromInvoice(Invoice $invoice): self
    {
        $invoice->loadMissing([
            'company.defaultBankAccount',
            'company.defaultEstablishment',
            'customer',
            'establishment',
            'reservation',
            'series',
            'lines.product',
            'lines.taxRate',
            'payments.allocations',
            'quote',
        ]);

        $currency = (string) $invoice->getAttribute('currency');
        $subtotalAmount = (string) $invoice->getAttribute('subtotal_amount');
        $taxAmount = (string) $invoice->getAttribute('tax_amount');
        $totalAmount = (string) $invoice->getAttribute('total_amount');

        /** @var Company|null $company */
        $company = $invoice->getRelationValue('company');

        /** @var Customer|null $customer */
        $customer = $invoice->getRelationValue('customer');

        /** @var \Illuminate\Database\Eloquent\Collection<int, InvoiceLine> $lines */
        $lines = $invoice->getRelationValue('lines') ?? collect();

        /** @var \Illuminate\Database\Eloquent\Collection<int, Payment> $payments */
        $payments = $invoice->getRelationValue('payments') ?? collect();

        /** @var InvoiceSeries|null $series */
        $series = $invoice->getRelationValue('series');

        /** @var InvoiceNumberReservation|null $reservation */
        $reservation = $invoice->getRelationValue('reservation');

        /** @var Quote|null $quote */
        $quote = $invoice->getRelationValue('quote');

        /** @var CompanyBankAccount|null $bankAccount */
        $bankAccount = $company?->defaultBankAccount;

        /** @var CompanyEstablishment|null $defaultEstablishment */
        $defaultEstablishment = $company?->defaultEstablishment;

        /** @var InvoiceType|null $documentType */
        $documentType = $invoice->getAttribute('document_type');

        /** @var InvoiceStatus|null $status */
        $status = $invoice->getAttribute('status');

        /** @var CompanyEstablishment|null $establishment */
        $establishment = $invoice->getRelationValue('establishment');

        $paidTotal = Money::fromDecimal((string) $payments->sum(fn (Payment $payment) => (float) $payment->amount), $currency);

        return new self(
            seller: $company?->toSnapshot() ?? new CompanyIdentitySnapshot,
            customer: $customer?->toSnapshot() ?? new CustomerIdentitySnapshot,
            lines: array_map(
                static fn (InvoiceLine $line): array => [
                    'sort_order' => $line->sort_order,
                    'description' => $line->description,
                    'quantity' => (string) $line->quantity,
                    'unit_price' => (string) $line->unit_price,
                    'discount_amount' => (string) $line->discount_amount,
                    'tax_rate' => (string) $line->tax_rate,
                    'total_amount' => (string) $line->total_amount,
                    'product' => [
                        'name' => $line->product?->name,
                        'sku' => $line->product?->sku,
                    ],
                ],
                array_values($lines->all())
            ),
            currency: $currency,
            documentType: $documentType,
            status: $status,
            number: $invoice->getAttribute('number'),
            issuedAt: $invoice->getAttribute('issued_at'),
            dueAt: $invoice->getAttribute('due_at'),
            series: $series ? [
                'id' => $series->id,
                'name' => $series->name,
                'prefix' => $series->prefix,
                'pattern' => $series->pattern,
                'current_sequence' => $series->current_sequence,
            ] : null,
            reservation: $reservation ? [
                'id' => $reservation->id,
                'number' => $reservation->number,
                'sequence' => $reservation->sequence,
                'reserved_at' => $reservation->reserved_at,
                'consumed_at' => $reservation->consumed_at,
            ] : null,
            bankAccount: $bankAccount ? [
                'bank_name' => $bankAccount->bank_name,
                'account_holder' => $bankAccount->account_holder,
                'iban' => $bankAccount->iban,
                'bic' => $bankAccount->bic,
            ] : null,
            establishment: $establishment ? [
                'name' => $establishment->name,
                'label' => $establishment->label,
            ] : ($defaultEstablishment ? [
                'name' => $defaultEstablishment->name,
                'label' => $defaultEstablishment->label,
            ] : null),
            payments: $payments->map(fn (Payment $payment): array => [
                'amount' => (string) $payment->amount,
                'method' => $payment->method,
                'status' => $payment->status,
                'paid_at' => $payment->paid_at,
                'reference' => $payment->reference,
                'notes' => $payment->notes,
            ])->all(),
            quote: $quote ? [
                'id' => $quote->id,
                'uuid_identifier' => $quote->uuid_identifier,
                'number' => $quote->number,
                'status' => $quote->status,
            ] : null,
            notes: $invoice->getAttribute('notes'),
            legalMentions: $company?->toSnapshot()->legalMentions?->toArray(),
            subtotal: Money::fromDecimal($subtotalAmount, $currency),
            taxTotal: Money::fromDecimal($taxAmount, $currency),
            total: Money::fromDecimal($totalAmount, $currency),
            paidTotal: $paidTotal,
            balanceDue: Money::fromDecimal(
                (string) max(0, (float) $totalAmount - (float) $paidTotal->amount),
                $currency
            ),
            locale: $company?->default_locale,
            publicShareUrl: $invoice->publicShareUrl(),
        );
    }

    public static function fromDraft(InvoiceDraftData $draft, InvoiceTotalsData $totals, array $context = []): self
    {
        $paidTotal = $context['paid_total'] ?? Money::zero($draft->currency);
        $paidTotalMoney = $paidTotal instanceof Money ? $paidTotal : Money::fromDecimal((string) $paidTotal, $draft->currency);

        return new self(
            seller: $draft->seller,
            customer: $draft->customer,
            lines: array_map(
                static fn ($line, int $index): array => [
                    'sort_order' => $index + 1,
                    'description' => $line->description,
                    'quantity' => (string) $line->quantity,
                    'unit_price' => $line->unitPrice->money->toDecimalString(),
                    'discount_amount' => $line->discount?->amount?->toDecimalString(),
                    'tax_rate' => (string) $line->taxRate,
                    'total_amount' => self::calculateLineTotal($line)->toDecimalString(),
                    'product' => $context['product'] ?? null,
                ],
                $draft->lines,
                array_keys($draft->lines)
            ),
            currency: $draft->currency,
            documentType: $draft->type,
            status: $context['status'] ?? null,
            number: $context['number'] ?? null,
            issuedAt: $context['issued_at'] ?? null,
            dueAt: $context['due_at'] ?? null,
            series: $context['series'] ?? null,
            reservation: $context['reservation'] ?? null,
            bankAccount: $context['bank_account'] ?? null,
            establishment: $context['establishment'] ?? null,
            payments: $context['payments'] ?? null,
            quote: $context['quote'] ?? null,
            notes: $context['notes'] ?? null,
            legalMentions: $context['legal_mentions'] ?? null,
            subtotal: $totals->subtotal,
            taxTotal: $totals->taxTotal,
            total: $totals->total,
            paidTotal: $paidTotalMoney,
            balanceDue: $context['balance_due'] ?? $totals->total->minus($paidTotalMoney),
            locale: $context['locale'] ?? null,
            publicShareUrl: $context['public_share_url'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toViewModel(): array
    {
        $seller = $this->seller->toArray();
        $customer = $this->customer->toArray();

        return [
            'invoice' => new Fluent([
                'currency' => $this->currency,
                'document_type' => $this->documentType,
                'status' => $this->status,
                'number' => $this->number,
                'issued_at' => $this->normalizeDate($this->issuedAt),
                'due_at' => $this->normalizeDate($this->dueAt),
                'company' => new Fluent([
                    'legal_name' => $seller['legal_name'] ?? null,
                    'display_name' => $seller['display_name'] ?? null,
                    'email' => $seller['contact_email'] ?? null,
                    'phone' => $seller['contact_phone'] ?? null,
                    'defaultBankAccount' => $this->bankAccount ? new Fluent($this->bankAccount) : null,
                    'defaultEstablishment' => $this->establishment ? new Fluent($this->establishment) : null,
                ]),
                'customer' => new Fluent([
                    'legal_name' => $customer['legal_name'] ?? null,
                    'full_name' => $customer['full_name'] ?? null,
                    'reference' => $customer['reference'] ?? null,
                    'email' => $customer['email'] ?? null,
                    'phone' => data_get($customer, 'contact.phone'),
                ]),
                'seller_snapshot' => $seller,
                'customer_snapshot' => $customer,
                'series' => $this->series ? new Fluent($this->series) : null,
                'reservation' => $this->reservation ? new Fluent($this->reservation) : null,
                'lines' => Collection::make($this->lines)->map(fn (array $line): Fluent => new Fluent([
                    'sort_order' => $line['sort_order'] ?? null,
                    'description' => $line['description'] ?? null,
                    'quantity' => $line['quantity'] ?? null,
                    'unit_price' => $line['unit_price'] ?? null,
                    'discount_amount' => $line['discount_amount'] ?? null,
                    'tax_rate' => $line['tax_rate'] ?? null,
                    'total_amount' => $line['total_amount'] ?? null,
                    'product' => isset($line['product']) && is_array($line['product']) ? new Fluent($line['product']) : null,
                ])),
                'payments' => Collection::make($this->payments ?? [])->map(fn (array $payment): Fluent => new Fluent([
                    'amount' => $payment['amount'] ?? null,
                    'method' => $this->normalizePaymentMethod($payment['method'] ?? null),
                    'status' => $this->normalizePaymentStatus($payment['status'] ?? null),
                    'paid_at' => $this->normalizeDate($payment['paid_at'] ?? null),
                    'reference' => $payment['reference'] ?? null,
                    'notes' => $payment['notes'] ?? null,
                ])),
                'quote' => $this->quote ? new Fluent($this->quote) : null,
                'notes' => $this->notes,
                'public_share_token' => $this->publicShareUrl ? true : null,
                'public_share_url' => $this->publicShareUrl,
                'public_share_expires_at' => null,
                'subtotal_amount' => $this->moneyValue($this->subtotal),
                'tax_amount' => $this->moneyValue($this->taxTotal),
                'total_amount' => $this->moneyValue($this->total),
                'paid_amount' => $this->moneyValue($this->paidTotal),
                'balance_due' => $this->moneyValue($this->balanceDue),
            ]),
        ];
    }

    private function normalizeDate(DateTimeInterface|string|null $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $value instanceof DateTimeInterface ? Carbon::instance($value) : Carbon::parse($value);
    }

    private function moneyValue(?Money $money): string
    {
        return $money?->toDecimalString() ?? '0.00';
    }

    private function normalizePaymentMethod(mixed $method): ?PaymentMethodType
    {
        if ($method instanceof PaymentMethodType || $method === null || $method === '') {
            return $method;
        }

        return PaymentMethodType::tryFrom((string) $method);
    }

    private function normalizePaymentStatus(mixed $status): ?PaymentStatus
    {
        if ($status instanceof PaymentStatus || $status === null || $status === '') {
            return $status;
        }

        return PaymentStatus::tryFrom((string) $status);
    }

    private static function calculateLineTotal(object $line): Money
    {
        $lineTotal = $line->unitPrice->money->multipliedBy((string) $line->quantity->value);

        if ($line->discount?->amount instanceof Money) {
            $lineTotal = $lineTotal->minus($line->discount->amount);
        }

        return $lineTotal;
    }
}
