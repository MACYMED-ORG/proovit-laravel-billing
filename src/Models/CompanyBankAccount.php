<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Company|null $company
 * @property-read CompanyEstablishment|null $establishment
 * @property-read string|null $bank_name
 * @property-read string|null $account_holder
 * @property-read string|null $iban
 * @property-read string|null $bic
 */
final class CompanyBankAccount extends BillingModel
{
    protected $table = 'billing_company_bank_accounts';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'bool',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(CompanyEstablishment::class, 'establishment_id');
    }
}
