<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class AddressData
{
    public function __construct(
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $postalCode = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
    ) {}

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'line1' => $this->line1,
            'line2' => $this->line2,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
        ];
    }

    public function isEmpty(): bool
    {
        return $this->line1 === null
            && $this->line2 === null
            && $this->postalCode === null
            && $this->city === null
            && $this->region === null
            && $this->country === null;
    }
}
