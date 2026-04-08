<?php

declare(strict_types=1);

use Proovit\Billing\Contracts\InvoiceCalculatorInterface;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

it('calculates totals across multiple tax rates and discounts', function (): void {
    $calculator = app(InvoiceCalculatorInterface::class);

    $draft = new InvoiceDraftData(
        seller: new CompanyIdentitySnapshot(
            legalName: 'ProovIT',
            displayName: 'ProovIT',
            address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR'),
        ),
        customer: new CustomerIdentitySnapshot(
            legalName: 'Client SARL',
            billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR'),
        ),
        lines: [
            new InvoiceLineData(
                description: 'Service A',
                quantity: new LineQuantity('2'),
                unitPrice: new UnitPrice(Money::fromDecimal('50.00', 'EUR')),
                taxRate: Percentage::fromDecimal('20'),
            ),
            new InvoiceLineData(
                description: 'Service B',
                quantity: new LineQuantity('1'),
                unitPrice: new UnitPrice(Money::fromDecimal('80.00', 'EUR')),
                taxRate: Percentage::fromDecimal('10'),
                discount: new DiscountValue(
                    percentage: Percentage::fromDecimal('0'),
                    amount: Money::fromDecimal('10.00', 'EUR'),
                ),
            ),
        ],
        currency: 'EUR',
        type: InvoiceType::Invoice,
    );

    $totals = $calculator->calculate($draft);

    expect($totals->subtotal->amount)->toBe('170.00');
    expect($totals->taxTotal->amount)->toBe('27.00');
    expect($totals->total->amount)->toBe('197.00');
});
