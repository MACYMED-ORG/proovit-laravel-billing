<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\ContactData;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\SequencePattern;
use Proovit\Billing\ValueObjects\Siren;
use Proovit\Billing\ValueObjects\Siret;
use Proovit\Billing\ValueObjects\UnitPrice;
use Proovit\Billing\ValueObjects\VatNumber;

final class InvoiceDraftPayloadMapper
{
    public function map(array $payload): InvoiceDraftData
    {
        $currency = (string) ($payload['currency'] ?? 'EUR');

        return new InvoiceDraftData(
            seller: $this->mapSeller((array) ($payload['seller'] ?? [])),
            customer: $this->mapCustomer((array) ($payload['customer'] ?? [])),
            lines: array_map(
                fn (array $line): InvoiceLineData => $this->mapLine($line, $currency),
                array_values((array) ($payload['lines'] ?? []))
            ),
            currency: $currency,
            type: InvoiceType::from((string) ($payload['type'] ?? InvoiceType::Invoice->value)),
            numbering: isset($payload['numbering']) && is_array($payload['numbering'])
                ? new SequencePattern(
                    prefix: (string) ($payload['numbering']['prefix'] ?? 'INV'),
                    suffix: $payload['numbering']['suffix'] ?? null,
                    pattern: (string) ($payload['numbering']['pattern'] ?? '{prefix}-{year}{month}-{sequence}'),
                    padding: (int) ($payload['numbering']['padding'] ?? 6),
                )
                : null,
        );
    }

    private function mapSeller(array $payload): CompanyIdentitySnapshot
    {
        return new CompanyIdentitySnapshot(
            legalName: $payload['legal_name'] ?? null,
            displayName: $payload['display_name'] ?? null,
            legalForm: $payload['legal_form'] ?? null,
            registrationCountry: $payload['registration_country'] ?? null,
            siren: isset($payload['siren']) && $payload['siren'] !== '' ? new Siren((string) $payload['siren']) : null,
            siret: isset($payload['siret']) && $payload['siret'] !== '' ? new Siret((string) $payload['siret']) : null,
            vatNumber: isset($payload['vat_number']) && $payload['vat_number'] !== '' ? new VatNumber((string) $payload['vat_number']) : null,
            contactEmail: $payload['contact_email'] ?? $payload['email'] ?? null,
            contactPhone: $payload['contact_phone'] ?? $payload['phone'] ?? null,
            address: $this->mapAddress((array) ($payload['address'] ?? $payload['billing_address'] ?? $payload['head_office_address'] ?? [])),
            legalMentions: null,
        );
    }

    private function mapCustomer(array $payload): CustomerIdentitySnapshot
    {
        return new CustomerIdentitySnapshot(
            legalName: $payload['legal_name'] ?? null,
            fullName: $payload['full_name'] ?? null,
            billingAddress: $this->mapAddress((array) ($payload['billing_address'] ?? [])),
            vatNumber: isset($payload['vat_number']) && $payload['vat_number'] !== '' ? new VatNumber((string) $payload['vat_number']) : null,
            reference: $payload['reference'] ?? null,
            email: $payload['email'] ?? null,
            contact: isset($payload['contact']) && is_array($payload['contact'])
                ? new ContactData(
                    name: $payload['contact']['name'] ?? null,
                    email: $payload['contact']['email'] ?? null,
                    phone: $payload['contact']['phone'] ?? null,
                )
                : null,
        );
    }

    private function mapLine(array $payload, string $currency): InvoiceLineData
    {
        $discount = isset($payload['discount']) && is_array($payload['discount'])
            ? new DiscountValue(
                percentage: new Percentage((string) ($payload['discount']['percentage'] ?? '0')),
                amount: isset($payload['discount']['amount']) && $payload['discount']['amount'] !== null
                    ? Money::fromDecimal((string) $payload['discount']['amount'], $currency)
                    : null,
            )
            : null;

        return new InvoiceLineData(
            description: (string) ($payload['description'] ?? ''),
            quantity: new LineQuantity((string) ($payload['quantity'] ?? '1')),
            unitPrice: new UnitPrice(Money::fromDecimal((string) ($payload['unit_price'] ?? '0'), $currency)),
            taxRate: new Percentage((string) ($payload['tax_rate'] ?? '0')),
            discount: $discount,
        );
    }

    private function mapAddress(array $payload): ?AddressData
    {
        if ($payload === []) {
            return null;
        }

        return new AddressData(
            line1: $payload['line1'] ?? null,
            line2: $payload['line2'] ?? null,
            postalCode: $payload['postal_code'] ?? $payload['postalCode'] ?? null,
            city: $payload['city'] ?? null,
            region: $payload['region'] ?? null,
            country: $payload['country'] ?? null,
        );
    }
}
