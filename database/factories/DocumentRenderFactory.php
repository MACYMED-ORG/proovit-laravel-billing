<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\DocumentRenderType;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\DocumentRender;
use Proovit\Billing\Models\Invoice;

/**
 * @extends Factory<DocumentRender>
 */
final class DocumentRenderFactory extends Factory
{
    protected $model = DocumentRender::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'invoice_id' => Invoice::factory(),
            'document_type' => 'invoice',
            'render_type' => DocumentRenderType::Pdf->value,
            'disk' => 'public',
            'path' => 'billing/invoices/demo.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => 1024,
        ];
    }
}
