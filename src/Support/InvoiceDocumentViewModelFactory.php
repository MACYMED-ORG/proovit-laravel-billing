<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Proovit\Billing\DTOs\Documents\InvoiceDocumentData;
use Proovit\Billing\Enums\PaymentMethodType;
use Proovit\Billing\Enums\PaymentStatus;

final class InvoiceDocumentViewModelFactory
{
    /**
     * @return array<string, mixed>
     */
    public function make(InvoiceDocumentData $document): array
    {
        $seller = $document->seller->toArray();
        $customer = $document->customer->toArray();

        return [
            'invoice' => new Fluent([
                'currency' => $document->currency,
                'document_type' => $document->documentType,
                'status' => $document->status,
                'number' => $document->number,
                'issued_at' => $this->normalizeDate($document->issuedAt),
                'due_at' => $this->normalizeDate($document->dueAt),
                'company' => new Fluent([
                    'legal_name' => $seller['legal_name'] ?? null,
                    'display_name' => $seller['display_name'] ?? null,
                    'email' => $seller['contact_email'] ?? null,
                    'phone' => $seller['contact_phone'] ?? null,
                    'defaultBankAccount' => $document->bankAccount ? new Fluent($document->bankAccount) : null,
                    'defaultEstablishment' => $document->establishment ? new Fluent($document->establishment) : null,
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
                'series' => $document->series ? new Fluent($document->series) : null,
                'reservation' => $document->reservation ? new Fluent($document->reservation) : null,
                'lines' => Collection::make($document->lines)->map(fn (array $line): Fluent => new Fluent([
                    'sort_order' => $line['sort_order'] ?? null,
                    'description' => $line['description'] ?? null,
                    'quantity' => $line['quantity'] ?? null,
                    'unit_price' => $line['unit_price'] ?? null,
                    'discount_amount' => $line['discount_amount'] ?? null,
                    'tax_rate' => $line['tax_rate'] ?? null,
                    'total_amount' => $line['total_amount'] ?? null,
                    'product' => isset($line['product']) && is_array($line['product']) ? new Fluent($line['product']) : null,
                ])),
                'payments' => Collection::make($document->payments ?? [])->map(fn (array $payment): Fluent => new Fluent([
                    'amount' => $payment['amount'] ?? null,
                    'method' => $this->normalizePaymentMethod($payment['method'] ?? null),
                    'status' => $this->normalizePaymentStatus($payment['status'] ?? null),
                    'paid_at' => $this->normalizeDate($payment['paid_at'] ?? null),
                    'reference' => $payment['reference'] ?? null,
                    'notes' => $payment['notes'] ?? null,
                ])),
                'quote' => $document->quote ? new Fluent($document->quote) : null,
                'notes' => $document->notes,
                'public_share_token' => $document->publicShareUrl ? true : null,
                'public_share_url' => $document->publicShareUrl,
                'public_share_expires_at' => null,
                'subtotal_amount' => $document->subtotal?->toDecimalString() ?? '0.00',
                'tax_amount' => $document->taxTotal?->toDecimalString() ?? '0.00',
                'total_amount' => $document->total?->toDecimalString() ?? '0.00',
                'paid_amount' => $document->paidTotal?->toDecimalString() ?? '0.00',
                'balance_due' => $document->balanceDue?->toDecimalString() ?? '0.00',
            ]),
        ];
    }

    private function normalizeDate(\DateTimeInterface|string|null $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $value instanceof \DateTimeInterface ? Carbon::instance($value) : Carbon::parse($value);
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
}
