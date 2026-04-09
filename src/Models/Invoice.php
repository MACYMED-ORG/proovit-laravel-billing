<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Proovit\Billing\Enums\DocumentRenderType;
use Proovit\Billing\Enums\InvoiceStatus;
use Proovit\Billing\Enums\InvoiceType;

/**
 * @property-read Company|null $company
 * @property-read CompanyEstablishment|null $establishment
 * @property-read Customer|null $customer
 * @property-read InvoiceSeries|null $series
 * @property-read InvoiceNumberReservation|null $reservation
 * @property-read Quote|null $quote
 * @property-read Collection<int, InvoiceLine> $lines
 * @property-read Collection<int, Payment> $payments
 * @property-read string|null $currency
 * @property-read string|null $subtotal_amount
 * @property-read string|null $tax_amount
 * @property-read string|null $total_amount
 * @property-read string|null $number
 * @property-read string|null $notes
 */
final class Invoice extends BillingModel
{
    protected $table = 'billing_invoices';

    protected $guarded = [];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'document_type' => InvoiceType::class,
        'quote_id' => 'integer',
        'public_shared_at' => 'datetime',
        'public_share_expires_at' => 'datetime',
        'seller_snapshot' => 'array',
        'customer_snapshot' => 'array',
        'issued_at' => 'date',
        'due_at' => 'date',
        'finalized_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(CompanyEstablishment::class, 'establishment_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(InvoiceSeries::class, 'invoice_series_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(InvoiceNumberReservation::class, 'invoice_number_reservation_id');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    public function publicShareUrl(?string $token = null): ?string
    {
        $token ??= $this->public_share_token;

        if (! $token) {
            return null;
        }

        return route('billing.public.invoices.show', ['token' => $token]);
    }

    public function isEditableDraft(): bool
    {
        $status = $this->getAttribute('status');
        $statusValue = $status instanceof InvoiceStatus ? $status->value : (string) $status;

        $documentType = $this->getAttribute('document_type');
        $documentTypeValue = $documentType instanceof InvoiceType ? $documentType->value : (string) $documentType;

        return $documentTypeValue === InvoiceType::Invoice->value
            && $statusValue === InvoiceStatus::Draft->value;
    }

    public function canManageLineItems(): bool
    {
        return $this->isEditableDraft();
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class, 'invoice_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function documentRenders(): HasMany
    {
        return $this->hasMany(DocumentRender::class, 'invoice_id');
    }

    public function latestPdfDocumentRender(): ?DocumentRender
    {
        /** @var DocumentRender|null $documentRender */
        $documentRender = $this->documentRenders()
            ->where('render_type', DocumentRenderType::Pdf->value)
            ->latest('id')
            ->first();

        return $documentRender;
    }

    public function latestPdfDocumentRenderPath(): ?string
    {
        return $this->latestPdfDocumentRender()?->getAttribute('path');
    }

    public function getLatestPdfDocumentRenderPathAttribute(): ?string
    {
        return $this->latestPdfDocumentRender()?->getAttribute('path');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Draft->value);
    }

    public function scopeFinalized(Builder $query): Builder
    {
        return $query->where('status', InvoiceStatus::Finalized->value);
    }

    public function scopeWithLines(Builder $query): Builder
    {
        return $query->with('lines');
    }

    public function scopeWithTotals(Builder $query): Builder
    {
        return $query->withCount('lines');
    }

    public function scopeWithPayments(Builder $query): Builder
    {
        return $query->with('payments');
    }

    public function scopeWithAuditTrail(Builder $query): Builder
    {
        return $query->withCount('payments');
    }
}
