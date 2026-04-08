<?php

declare(strict_types=1);

use Proovit\Billing\Actions\Invoices\GenerateFacturXAction;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

it('builds a factur-x compatible stub output from a draft invoice', function (): void {
    $xml = app(GenerateFacturXAction::class)->handle(
        new InvoiceDraftData(
            seller: new CompanyIdentitySnapshot(
                legalName: 'ProovIT SAS',
                address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR'),
            ),
            customer: new CustomerIdentitySnapshot(
                legalName: 'Client SARL',
                billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR'),
            ),
            lines: [
                new InvoiceLineData(
                    description: 'Service',
                    quantity: new LineQuantity('1'),
                    unitPrice: new UnitPrice(Money::fromDecimal('100.00', 'EUR')),
                    taxRate: Percentage::fromDecimal('20'),
                ),
            ],
            type: InvoiceType::Invoice,
        )
    );

    expect($xml)->toContain('factur-x');
    expect($xml)->toContain('draft-lines="1"');
    expect($xml)->toContain('currency="EUR"');
});
