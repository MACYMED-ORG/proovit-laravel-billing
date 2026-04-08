<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\CompanyBankAccount;
use Proovit\Billing\Models\CompanyEstablishment;

uses(RefreshDatabase::class);

it('stores company establishments and bank accounts and resolves the defaults', function (): void {
    $company = Company::create([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'legal_form' => 'SAS',
        'registration_country' => 'FR',
        'siren' => '123456789',
        'siret' => '12345678900011',
        'vat_number' => 'FR12345678901',
        'intracommunity_vat_number' => 'FR12345678901',
        'head_office_address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        'billing_address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        'email' => 'billing@proovit.test',
        'phone' => '+33100000000',
        'default_currency' => 'EUR',
        'default_locale' => 'fr',
        'timezone' => 'Europe/Paris',
        'default_payment_terms' => 30,
        'invoice_prefix' => 'INV',
        'invoice_sequence_pattern' => '{prefix}-{year}{month}-{sequence}',
    ]);

    $mainOffice = CompanyEstablishment::create([
        'company_id' => $company->id,
        'name' => 'Siège',
        'code' => 'HQ',
        'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        'email' => 'hq@proovit.test',
        'phone' => '+33100000000',
        'is_default' => true,
    ]);

    $bankAccount = CompanyBankAccount::create([
        'company_id' => $company->id,
        'establishment_id' => $mainOffice->id,
        'label' => 'Compte principal',
        'iban' => 'FR7630006000011234567890189',
        'bic' => 'AGRIFRPP',
        'bank_name' => 'Bank',
        'account_holder' => 'ProovIT SAS',
        'is_default' => true,
    ]);

    expect($company->defaultEstablishment->is($mainOffice))->toBeTrue();
    expect($company->defaultBankAccount->is($bankAccount))->toBeTrue();
    expect($company->toSnapshot()->toArray())->toMatchArray([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'full_address' => [
            'line1' => '1 rue de Paris',
            'line2' => null,
            'postal_code' => null,
            'city' => 'Paris',
            'region' => null,
            'country' => 'FR',
        ],
    ]);
});
