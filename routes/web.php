<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Proovit\Billing\Http\Controllers\Web\Invoices\ShowSharedInvoiceController;

$prefix = trim(config('billing.web.prefix', 'billing'), '/');

Route::prefix($prefix)
    ->middleware((array) config('billing.web.middleware', ['web']))
    ->as('billing.')
    ->group(function (): void {
        if ((bool) config('billing.public_shares.enabled', true)) {
            Route::get('public/invoices/{token}', ShowSharedInvoiceController::class)
                ->middleware('signed')
                ->name('public.invoices.show');
        }
    });
