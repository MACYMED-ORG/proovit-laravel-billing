<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Models\AuditLog;
use Proovit\Billing\Models\Company;

/**
 * @extends Factory<AuditLog>
 */
final class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => null,
            'auditable_type' => 'invoice',
            'auditable_id' => 1,
            'event' => 'created',
            'context' => [
                'source' => 'factory',
            ],
            'created_at' => now(),
        ];
    }
}
