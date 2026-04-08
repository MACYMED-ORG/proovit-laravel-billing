<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\LegalMentionSet;
use Proovit\Billing\ValueObjects\Siren;
use Proovit\Billing\ValueObjects\Siret;
use Proovit\Billing\ValueObjects\VatNumber;

/**
 * @property-read CompanyBankAccount|null $defaultBankAccount
 * @property-read CompanyEstablishment|null $defaultEstablishment
 * @property-read string|null $legal_name
 * @property-read string|null $display_name
 * @property-read string|null $legal_form
 * @property-read string|null $registration_country
 * @property-read string|null $siren
 * @property-read string|null $siret
 * @property-read string|null $vat_number
 * @property-read string|null $email
 * @property-read string|null $phone
 * @property-read string|null $default_locale
 * @property-read string|null $default_currency
 * @property-read array<string, mixed>|null $head_office_address
 * @property-read array<string, mixed>|null $billing_address
 */
final class Company extends BillingModel
{
    protected $table = 'billing_companies';

    protected $guarded = [];

    protected $casts = [
        'head_office_address' => 'array',
        'billing_address' => 'array',
    ];

    public function establishments(): HasMany
    {
        return $this->hasMany(CompanyEstablishment::class, 'company_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(CompanyBankAccount::class, 'company_id');
    }

    public function defaultBankAccount(): HasOne
    {
        return $this->hasOne(CompanyBankAccount::class, 'company_id')->where('is_default', true);
    }

    public function defaultEstablishment(): HasOne
    {
        return $this->hasOne(CompanyEstablishment::class, 'company_id')->where('is_default', true);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'company_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'company_id');
    }

    public function scopeSearchableReference(Builder $query): Builder
    {
        return $query->orderBy('legal_name');
    }

    public function toSnapshot(?LegalMentionSet $legalMentions = null): CompanyIdentitySnapshot
    {
        return new CompanyIdentitySnapshot(
            legalName: $this->legal_name,
            displayName: $this->display_name,
            legalForm: $this->legal_form,
            registrationCountry: $this->registration_country,
            siren: $this->siren ? new Siren($this->siren) : null,
            siret: $this->siret ? new Siret($this->siret) : null,
            vatNumber: $this->vat_number ? new VatNumber($this->vat_number) : null,
            contactEmail: $this->email,
            contactPhone: $this->phone,
            address: $this->resolveAddressData(),
            legalMentions: $legalMentions,
        );
    }

    private function resolveAddressData(): ?AddressData
    {
        $address = $this->billing_address ?? $this->head_office_address;

        if (! is_array($address)) {
            return null;
        }

        return new AddressData(
            line1: $address['line1'] ?? null,
            line2: $address['line2'] ?? null,
            postalCode: $address['postal_code'] ?? null,
            city: $address['city'] ?? null,
            region: $address['region'] ?? null,
            country: $address['country'] ?? null,
        );
    }
}
