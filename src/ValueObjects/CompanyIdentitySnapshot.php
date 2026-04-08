<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class CompanyIdentitySnapshot
{
    public function __construct(
        public ?string $legalName = null,
        public ?string $displayName = null,
        public ?string $legalForm = null,
        public ?string $registrationCountry = null,
        public ?Siren $siren = null,
        public ?Siret $siret = null,
        public ?VatNumber $vatNumber = null,
        public ?string $contactEmail = null,
        public ?string $contactPhone = null,
        public ?AddressData $address = null,
        public ?LegalMentionSet $legalMentions = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'legal_name' => $this->legalName,
            'display_name' => $this->displayName,
            'legal_form' => $this->legalForm,
            'registration_country' => $this->registrationCountry,
            'siren' => $this->siren?->__toString(),
            'siret' => $this->siret?->__toString(),
            'vat_number' => $this->vatNumber?->__toString(),
            'full_address' => $this->address?->toArray(),
            'address' => $this->address?->toArray(),
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'legal_mentions' => $this->legalMentions?->toArray(),
        ];
    }
}
