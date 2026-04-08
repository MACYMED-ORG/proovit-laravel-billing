<?php

declare(strict_types=1);

namespace Proovit\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Proovit\Billing\Enums\ReminderChannel;
use Proovit\Billing\Models\Company;
use Proovit\Billing\Models\Invoice;
use Proovit\Billing\Models\Reminder;

/**
 * @extends Factory<Reminder>
 */
final class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'invoice_id' => Invoice::factory(),
            'channel' => ReminderChannel::Email->value,
            'status' => 'draft',
            'sent_at' => null,
        ];
    }
}
