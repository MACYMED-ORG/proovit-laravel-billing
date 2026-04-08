<?php

declare(strict_types=1);

use Proovit\Billing\Actions\Invoices\GenerateInvoicePdfAction;
use Proovit\Billing\Builders\Documents\InvoiceDocumentBuilder;
use Proovit\Billing\DTOs\InvoiceDraftData;
use Proovit\Billing\DTOs\InvoiceLineData;
use Proovit\Billing\DTOs\InvoiceTotalsData;
use Proovit\Billing\Enums\InvoiceType;
use Proovit\Billing\ValueObjects\AddressData;
use Proovit\Billing\ValueObjects\CompanyIdentitySnapshot;
use Proovit\Billing\ValueObjects\CustomerIdentitySnapshot;
use Proovit\Billing\ValueObjects\DiscountValue;
use Proovit\Billing\ValueObjects\LineQuantity;
use Proovit\Billing\ValueObjects\Money;
use Proovit\Billing\ValueObjects\Percentage;
use Proovit\Billing\ValueObjects\UnitPrice;

it('builds and renders invoice documents with the fluent builder', function (): void {
    $document = InvoiceDocumentBuilder::make()
        ->withSeller([
            'legal_name' => 'ProovIT SAS',
            'display_name' => 'ProovIT',
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ])
        ->withCustomer([
            'legal_name' => 'Client SARL',
            'reference' => 'CLI-001',
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ])
        ->addLine([
            'description' => 'Consulting',
            'quantity' => '1',
            'unit_price' => '250.00',
            'tax_rate' => '20',
        ])
        ->withNumber('INV-2026-0001')
        ->withIssuedAt(now())
        ->withDueAt(now()->addDays(30))
        ->withLocale('fr')
        ->build();

    expect($document->number)->toBe('INV-2026-0001');
    expect($document->currency)->toBe('EUR');

    $html = app(GenerateInvoicePdfAction::class)->handle($document);

    expect($html)->toContain('PDF:billing::pdf.invoice');
    expect($html)->toContain('ProovIT SAS');
    expect($html)->toContain('Client SARL');
});

it('builds invoice documents from drafts with contextual totals and payments', function (): void {
    $draft = new InvoiceDraftData(
        seller: new CompanyIdentitySnapshot(
            legalName: 'ProovIT SAS',
            displayName: 'ProovIT',
            address: new AddressData(line1: '1 rue de Paris', city: 'Paris', country: 'FR'),
        ),
        customer: new CustomerIdentitySnapshot(
            legalName: 'Client SARL',
            billingAddress: new AddressData(line1: '2 avenue des Tests', city: 'Lyon', country: 'FR'),
            reference: 'CLI-001',
        ),
        lines: [
            new InvoiceLineData(
                description: 'Service',
                quantity: new LineQuantity('2'),
                unitPrice: new UnitPrice(Money::fromDecimal('100.00', 'EUR')),
                discount: new DiscountValue(
                    percentage: Percentage::fromDecimal('5'),
                    amount: Money::fromDecimal('10.00', 'EUR')
                ),
                taxRate: Percentage::fromDecimal('20'),
            ),
        ],
        currency: 'EUR',
        type: InvoiceType::Invoice,
    );

    $document = InvoiceDocumentBuilder::fromDraft(
        $draft,
        new InvoiceTotalsData(
            subtotal: Money::fromDecimal('190.00', 'EUR'),
            taxTotal: Money::fromDecimal('38.00', 'EUR'),
            total: Money::fromDecimal('228.00', 'EUR'),
        ),
        [
            'number' => 'INV-2026-0002',
            'issued_at' => now(),
            'due_at' => now()->addDays(15),
            'payments' => [
                ['amount' => '100.00'],
                ['amount' => '50.00'],
            ],
            'public_share_url' => 'https://example.test/share/invoice-2',
            'locale' => 'fr',
        ]
    )->build();

    expect($document->number)->toBe('INV-2026-0002');
    expect($document->subtotal?->toDecimalString())->toBe('190.00');
    expect($document->paidTotal?->toDecimalString())->toBe('150.00');
    expect($document->balanceDue?->toDecimalString())->toBe('78.00');
    expect($document->publicShareUrl)->toBe('https://example.test/share/invoice-2');
    expect($document->payments)->toHaveCount(2);
});

it('adds multiple lines fluently and validates the resulting document', function (): void {
    $builder = InvoiceDocumentBuilder::make()
        ->withSeller([
            'legal_name' => 'ProovIT SAS',
            'display_name' => 'ProovIT',
            'address' => ['line1' => '1 rue de Paris', 'city' => 'Paris', 'country' => 'FR'],
        ])
        ->withCustomer([
            'legal_name' => 'Client SARL',
            'reference' => 'CLI-002',
            'billing_address' => ['line1' => '2 avenue des Tests', 'city' => 'Lyon', 'country' => 'FR'],
        ])
        ->addLines([
            [
                'description' => 'Consulting',
                'quantity' => '1',
                'unit_price' => '120.00',
                'tax_rate' => '20',
            ],
            [
                'description' => 'Support',
                'quantity' => '3',
                'unit_price' => '30.00',
                'tax_rate' => '20',
            ],
        ]);

    $document = $builder->build();

    expect($document->lines)->toHaveCount(2);
    expect($document->toViewModel()['invoice']->lines)->toHaveCount(2);
});

it('validates that the fluent builder has the required minimum data', function (): void {
    expect(fn () => InvoiceDocumentBuilder::make()->build())
        ->toThrow(LogicException::class);
});
