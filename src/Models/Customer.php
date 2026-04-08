<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\ContactData;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\VatNumber;

/**
 * @property-read Company|null $company
 * @property-read string|null $legal_name
 * @property-read string|null $full_name
 * @property-read string|null $reference
 * @property-read string|null $email
 * @property-read string|null $phone
 * @property-read string|null $vat_number
 * @property-read array<string, mixed>|null $billing_address
 * @property-read array<string, mixed>|null $shipping_address
 */
final class Customer extends BillingModel
{
    protected $table = 'billing_customers';

    protected $guarded = [];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function scopeLatest(Builder $query): Builder
    {
        return $query->latest('id');
    }

    public function toSnapshot(): CustomerIdentitySnapshot
    {
        return new CustomerIdentitySnapshot(
            legalName: $this->legal_name,
            fullName: $this->full_name,
            billingAddress: $this->billing_address ? new AddressData(
                line1: $this->billing_address['line1'] ?? null,
                line2: $this->billing_address['line2'] ?? null,
                postalCode: $this->billing_address['postal_code'] ?? null,
                city: $this->billing_address['city'] ?? null,
                region: $this->billing_address['region'] ?? null,
                country: $this->billing_address['country'] ?? null,
            ) : null,
            vatNumber: $this->vat_number ? new VatNumber($this->vat_number) : null,
            reference: $this->reference,
            email: $this->email,
            contact: $this->full_name || $this->email || $this->phone ? new ContactData(
                name: $this->full_name ?? $this->legal_name,
                email: $this->email,
                phone: $this->phone,
            ) : null,
        );
    }
}
