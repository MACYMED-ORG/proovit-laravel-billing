<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class CustomerIdentitySnapshot
{
    public function __construct(
        public ?string $legalName = null,
        public ?string $fullName = null,
        public ?AddressData $billingAddress = null,
        public ?VatNumber $vatNumber = null,
        public ?string $reference = null,
        public ?string $email = null,
        public ?ContactData $contact = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'legal_name' => $this->legalName,
            'full_name' => $this->fullName,
            'legal_name_or_full_name' => $this->legalName ?? $this->fullName,
            'billing_address' => $this->billingAddress?->toArray(),
            'vat_number' => $this->vatNumber?->__toString(),
            'reference' => $this->reference,
            'email' => $this->email,
            'contact' => $this->contact?->toArray(),
        ];
    }
}
