<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Proovit\Billing\Actions\Documents\SnapshotDocumentPartiesAction;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Customer;

uses(RefreshDatabase::class);

it('builds flat seller and customer snapshots from live models', function (): void {
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

    $customer = Customer::create([
        'company_id' => $company->id,
        'legal_name' => 'Client SARL',
        'full_name' => 'Client SARL',
        'reference' => 'CLI-001',
        'email' => 'client@example.test',
        'vat_number' => 'FR10987654321',
        'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
    ]);

    $snapshots = app(SnapshotDocumentPartiesAction::class);

    expect($snapshots->companySnapshot($company))->toMatchArray([
        'legal_name' => 'ProovIT SAS',
        'display_name' => 'ProovIT',
        'registration_country' => 'FR',
        'siren' => '123456789',
        'siret' => '12345678900011',
        'vat_number' => 'FR12345678901',
        'contact_email' => 'billing@proovit.test',
        'contact_phone' => '+33100000000',
    ]);

    expect($snapshots->customerSnapshot($customer))->toMatchArray([
        'legal_name_or_full_name' => 'Client SARL',
        'reference' => 'CLI-001',
        'email' => 'client@example.test',
    ]);
});
