<?php

declare(strict_types=1);

namespace Proovit\Billing\Builders\Documents;

use DateTimeInterface;
use Illuminate\Support\Arr;
use LogicException;
use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\DTOs\InvoiceTotalsData;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\ContactData;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\DueDatePolicy;
use Proovit\Billing\ValueObjects\LegalMentionSet;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\SequencePattern;
use Proovit\Billing\ValueObjects\Siren;
use Proovit\Billing\ValueObjects\Siret;
use Proovit\Billing\ValueObjects\UnitPrice;
use Proovit\Billing\ValueObjects\VatNumber;

final class InvoiceDocumentBuilder
{
    private ?CompanyIdentitySnapshot $seller = null;

    private ?CustomerIdentitySnapshot $customer = null;

    /**
     * @var array<int, InvoiceLineData>
     */
    private array $lines = [];

    private string $currency = 'EUR';

    private ?InvoiceType $documentType = InvoiceType::Invoice;

    private ?InvoiceStatus $status = null;

    private ?string $number = null;

    private DateTimeInterface|string|null $issuedAt = null;

    private DateTimeInterface|string|null $dueAt = null;

    private mixed $series = null;

    private mixed $reservation = null;

    private mixed $bankAccount = null;

    private mixed $establishment = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $payments = [];

    private mixed $quote = null;

    private ?string $notes = null;

    /**
     * @var array<int, string>|null
     */
    private ?array $legalMentions = null;

    private ?Money $paidTotal = null;

    private ?string $locale = null;

    private ?string $publicShareUrl = null;

    private ?DueDatePolicy $dueDatePolicy = null;

    private ?SequencePattern $numbering = null;

    private ?InvoiceTotalsData $presetTotals = null;

    public static function make(): self
    {
        return new self;
    }

    public static function fromDraft(InvoiceDraftData $draft, InvoiceTotalsData $totals, array $context = []): self
    {
        $builder = new self;
        $builder->withSeller($draft->seller);
        $builder->withCustomer($draft->customer);
        $builder->withLines($draft->lines);
        $builder->withCurrency($draft->currency);
        $builder->withDocumentType($draft->type);
        $builder->withDueDatePolicy($draft->dueDatePolicy);
        $builder->withNumbering($draft->numbering);
        $builder->withTotals($totals);

        if (array_key_exists('status', $context)) {
            $builder->withStatus($context['status']);
        }

        if (array_key_exists('number', $context)) {
            $builder->withNumber($context['number']);
        }

        if (array_key_exists('issued_at', $context)) {
            $builder->withIssuedAt($context['issued_at']);
        }

        if (array_key_exists('due_at', $context)) {
            $builder->withDueAt($context['due_at']);
        }

        if (array_key_exists('series', $context)) {
            $builder->withSeries($context['series']);
        }

        if (array_key_exists('reservation', $context)) {
            $builder->withReservation($context['reservation']);
        }

        if (array_key_exists('bank_account', $context)) {
            $builder->withBankAccount($context['bank_account']);
        }

        if (array_key_exists('establishment', $context)) {
            $builder->withEstablishment($context['establishment']);
        }

        if (array_key_exists('payments', $context)) {
            $builder->withPayments((array) $context['payments']);
        }

        if (array_key_exists('quote', $context)) {
            $builder->withQuote($context['quote']);
        }

        if (array_key_exists('notes', $context)) {
            $builder->withNotes($context['notes']);
        }

        if (array_key_exists('legal_mentions', $context)) {
            $builder->withLegalMentions($context['legal_mentions']);
        }

        if (array_key_exists('paid_total', $context)) {
            $builder->withPaidTotal($context['paid_total']);
        }

        if (array_key_exists('locale', $context)) {
            $builder->withLocale($context['locale']);
        }

        if (array_key_exists('public_share_url', $context)) {
            $builder->withPublicShareUrl($context['public_share_url']);
        }

        return $builder;
    }

    public function withSeller(CompanyIdentitySnapshot|array $seller): self
    {
        $this->seller = $seller instanceof CompanyIdentitySnapshot ? $seller : $this->normalizeSeller($seller);

        return $this;
    }

    public function withCustomer(CustomerIdentitySnapshot|array $customer): self
    {
        $this->customer = $customer instanceof CustomerIdentitySnapshot ? $customer : $this->normalizeCustomer($customer);

        return $this;
    }

    public function withLine(InvoiceLineData|array $line): self
    {
        $this->lines[] = $line instanceof InvoiceLineData ? $line : $this->normalizeLine($line);

        return $this;
    }

    /**
     * Alias for withLine() to support a more natural fluent API.
     */
    public function addLine(InvoiceLineData|array $line): self
    {
        return $this->withLine($line);
    }

    /**
     * @param  array<int, InvoiceLineData|array<string, mixed>>  $lines
     */
    public function withLines(array $lines): self
    {
        $this->lines = [];

        foreach ($lines as $line) {
            $this->withLine($line);
        }

        return $this;
    }

    /**
     * Alias for withLines() to support a more natural fluent API.
     *
     * @param  array<int, InvoiceLineData|array<string, mixed>>  $lines
     */
    public function addLines(array $lines): self
    {
        return $this->withLines($lines);
    }

    public function withCurrency(string $currency): self
    {
        $this->currency = strtoupper(trim($currency));

        return $this;
    }

    public function withDocumentType(InvoiceType|string|null $documentType): self
    {
        $this->documentType = $this->normalizeInvoiceType($documentType);

        return $this;
    }

    public function withStatus(InvoiceStatus|string|null $status): self
    {
        $this->status = $this->normalizeInvoiceStatus($status);

        return $this;
    }

    public function withNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function withIssuedAt(DateTimeInterface|string|null $issuedAt): self
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    public function withDueAt(DateTimeInterface|string|null $dueAt): self
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    public function withSeries(mixed $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function withReservation(mixed $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function withBankAccount(mixed $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function withEstablishment(mixed $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>>  $payments
     */
    public function withPayments(array $payments): self
    {
        $this->payments = $payments;

        return $this;
    }

    public function withQuote(mixed $quote): self
    {
        $this->quote = $quote;

        return $this;
    }

    public function withNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @param  array<int, string>|LegalMentionSet|null  $legalMentions
     */
    public function withLegalMentions(array|LegalMentionSet|null $legalMentions): self
    {
        $this->legalMentions = $legalMentions instanceof LegalMentionSet
            ? $legalMentions->toArray()
            : $legalMentions;

        return $this;
    }

    public function withPaidTotal(Money|int|float|string|null $paidTotal): self
    {
        if ($paidTotal instanceof Money) {
            $this->paidTotal = $paidTotal;

            return $this;
        }

        if ($paidTotal === null) {
            $this->paidTotal = null;

            return $this;
        }

        $this->paidTotal = Money::fromDecimal((string) $paidTotal, $this->currency);

        return $this;
    }

    public function withLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function withPublicShareUrl(?string $publicShareUrl): self
    {
        $this->publicShareUrl = $publicShareUrl;

        return $this;
    }

    public function withDueDatePolicy(?DueDatePolicy $dueDatePolicy): self
    {
        $this->dueDatePolicy = $dueDatePolicy;

        return $this;
    }

    public function withNumbering(?SequencePattern $numbering): self
    {
        $this->numbering = $numbering;

        return $this;
    }

    public function withTotals(InvoiceTotalsData $totals): self
    {
        $this->presetTotals = $totals;

        return $this;
    }

    public function validate(): void
    {
        if (! $this->seller instanceof CompanyIdentitySnapshot) {
            throw new LogicException('Invoice seller is required.');
        }

        if (! $this->customer instanceof CustomerIdentitySnapshot) {
            throw new LogicException('Invoice customer is required.');
        }

        if ($this->lines === []) {
            throw new LogicException('At least one invoice line is required.');
        }
    }

    public function toDraft(): InvoiceDraftData
    {
        $this->validate();

        return new InvoiceDraftData(
            seller: $this->seller,
            customer: $this->customer,
            lines: $this->lines,
            currency: $this->currency,
            dueDatePolicy: $this->dueDatePolicy,
            type: $this->documentType ?? InvoiceType::Invoice,
            numbering: $this->numbering,
        );
    }

    public function build(?InvoiceTotalsData $totals = null): InvoiceDocumentData
    {
        $draft = $this->toDraft();
        $totals ??= $this->presetTotals ?? $this->resolveCalculator()->calculate($draft);
        $context = $this->buildContext();

        $context['paid_total'] = $this->paidTotal ?? $this->resolvePaidTotal();

        return InvoiceDocumentData::fromDraft($draft, $totals, $context);
    }

    public function render(?InvoiceTotalsData $totals = null): string
    {
        return app(GenerateInvoicePdfAction::class)->handle($this->build($totals));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(?InvoiceTotalsData $totals = null): array
    {
        return $this->build($totals)->toViewModel();
    }

    private function resolveCalculator(): InvoiceCalculatorInterface
    {
        return app(InvoiceCalculatorInterface::class);
    }

    private function resolvePaidTotal(): Money
    {
        if ($this->paidTotal instanceof Money) {
            return $this->paidTotal;
        }

        if ($this->payments === []) {
            return Money::zero($this->currency);
        }

        $total = Money::zero($this->currency);

        foreach ($this->payments as $payment) {
            if (! isset($payment['amount'])) {
                continue;
            }

            $total = $total->plus(Money::fromDecimal((string) $payment['amount'], $this->currency));
        }

        return $total;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildContext(): array
    {
        return array_filter([
            'status' => $this->status,
            'number' => $this->number,
            'issued_at' => $this->issuedAt,
            'due_at' => $this->dueAt,
            'series' => $this->series,
            'reservation' => $this->reservation,
            'bank_account' => $this->bankAccount,
            'establishment' => $this->establishment,
            'payments' => $this->payments,
            'quote' => $this->quote,
            'notes' => $this->notes,
            'legal_mentions' => $this->legalMentions,
            'locale' => $this->locale,
            'public_share_url' => $this->publicShareUrl,
        ], static fn (mixed $value): bool => $value !== null && $value !== []);
    }

    /**
     * @param  array<string, mixed>  $seller
     */
    private function normalizeSeller(array $seller): CompanyIdentitySnapshot
    {
        return new CompanyIdentitySnapshot(
            legalName: $seller['legal_name'] ?? null,
            displayName: $seller['display_name'] ?? null,
            legalForm: $seller['legal_form'] ?? null,
            registrationCountry: $seller['registration_country'] ?? null,
            siren: isset($seller['siren']) && $seller['siren'] !== '' ? new Siren((string) $seller['siren']) : null,
            siret: isset($seller['siret']) && $seller['siret'] !== '' ? new Siret((string) $seller['siret']) : null,
            vatNumber: isset($seller['vat_number']) && $seller['vat_number'] !== '' ? new VatNumber((string) $seller['vat_number']) : null,
            contactEmail: $seller['contact_email'] ?? $seller['email'] ?? null,
            contactPhone: $seller['contact_phone'] ?? $seller['phone'] ?? null,
            address: $this->normalizeAddress($seller['address'] ?? $seller['full_address'] ?? null),
            legalMentions: isset($seller['legal_mentions']) ? $this->normalizeLegalMentions($seller['legal_mentions']) : null,
        );
    }

    /**
     * @param  array<string, mixed>  $customer
     */
    private function normalizeCustomer(array $customer): CustomerIdentitySnapshot
    {
        return new CustomerIdentitySnapshot(
            legalName: $customer['legal_name'] ?? null,
            fullName: $customer['full_name'] ?? null,
            billingAddress: $this->normalizeAddress($customer['billing_address'] ?? null),
            vatNumber: isset($customer['vat_number']) && $customer['vat_number'] !== '' ? new VatNumber((string) $customer['vat_number']) : null,
            reference: $customer['reference'] ?? null,
            email: $customer['email'] ?? null,
            contact: isset($customer['contact']) && is_array($customer['contact']) ? new ContactData(
                name: $customer['contact']['name'] ?? null,
                email: $customer['contact']['email'] ?? null,
                phone: $customer['contact']['phone'] ?? null,
            ) : null,
        );
    }

    /**
     * @param  array<string, mixed>  $line
     */
    private function normalizeLine(array $line): InvoiceLineData
    {
        $description = (string) ($line['description'] ?? '');

        if ($description === '') {
            throw new LogicException('Invoice line description is required.');
        }

        $quantity = $line['quantity'] ?? $line['qty'] ?? null;
        $unitPrice = $line['unit_price'] ?? $line['unitPrice'] ?? null;
        $taxRate = $line['tax_rate'] ?? $line['taxRate'] ?? null;

        if ($quantity === null || $unitPrice === null || $taxRate === null) {
            throw new LogicException('Invoice line quantity, unit price and tax rate are required.');
        }

        $discount = null;

        if (array_key_exists('discount', $line) && is_array($line['discount'])) {
            $discount = new DiscountValue(
                percentage: Percentage::fromDecimal((string) ($line['discount']['percentage'] ?? 0)),
                amount: isset($line['discount']['amount']) ? Money::fromDecimal((string) $line['discount']['amount'], $this->currency) : null,
            );
        } elseif (array_key_exists('discount_amount', $line) || array_key_exists('discount_percentage', $line)) {
            $discountAmount = $line['discount_amount'] ?? null;
            $discountPercentage = $line['discount_percentage'] ?? 0;
            $discount = new DiscountValue(
                percentage: Percentage::fromDecimal((string) $discountPercentage),
                amount: $discountAmount !== null ? Money::fromDecimal((string) $discountAmount, $this->currency) : null,
            );
        }

        return new InvoiceLineData(
            description: $description,
            quantity: $quantity instanceof LineQuantity ? $quantity : new LineQuantity((string) $quantity),
            unitPrice: $unitPrice instanceof UnitPrice ? $unitPrice : new UnitPrice(Money::fromDecimal((string) $unitPrice, $this->currency)),
            taxRate: $taxRate instanceof Percentage ? $taxRate : Percentage::fromDecimal((string) $taxRate),
            discount: $discount,
        );
    }

    private function normalizeAddress(mixed $address): ?AddressData
    {
        if ($address instanceof AddressData) {
            return $address;
        }

        if (! is_array($address)) {
            return null;
        }

        return new AddressData(
            line1: $address['line1'] ?? null,
            line2: $address['line2'] ?? null,
            postalCode: $address['postal_code'] ?? $address['postalCode'] ?? null,
            city: $address['city'] ?? null,
            region: $address['region'] ?? null,
            country: $address['country'] ?? null,
        );
    }

    private function normalizeLegalMentions(mixed $legalMentions): ?LegalMentionSet
    {
        if ($legalMentions instanceof LegalMentionSet) {
            return $legalMentions;
        }

        if (! is_array($legalMentions)) {
            return null;
        }

        return new LegalMentionSet(array_values(array_map(static fn (mixed $item): string => (string) $item, Arr::wrap($legalMentions))));
    }

    private function normalizeInvoiceType(InvoiceType|string|null $documentType): ?InvoiceType
    {
        if ($documentType instanceof InvoiceType || $documentType === null) {
            return $documentType;
        }

        return InvoiceType::tryFrom($documentType) ?? InvoiceType::Invoice;
    }

    private function normalizeInvoiceStatus(InvoiceStatus|string|null $status): ?InvoiceStatus
    {
        if ($status instanceof InvoiceStatus || $status === null) {
            return $status;
        }

        return InvoiceStatus::tryFrom($status);
    }
}
