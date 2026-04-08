<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\EInvoiceFormat;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\EInvoiceExport;
use Proovit\Billing\Models\Invoice;

/**
 * @extends Factory<EInvoiceExport>
 */
final class EInvoiceExportFactory extends Factory
{
    protected $model = EInvoiceExport::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'invoice_id' => Invoice::factory(),
            'format' => EInvoiceFormat::FacturX->value,
            'status' => 'pending',
            'disk' => 'public',
            'path' => 'billing/invoices/demo-factur-x.xml',
        ];
    }
}
