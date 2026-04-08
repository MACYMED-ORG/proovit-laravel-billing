<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Proovit\Billing\Http\Controllers\Web\Invoices\ShowInvoicePreviewController;
use Proovit\Billing\Http\Controllers\Web\Invoices\ShowInvoicePrintController;
use Proovit\Billing\Http\Controllers\Web\Invoices\ShowSharedInvoiceController;

$prefix = trim(config('billing.web.prefix', 'billing'), '/');

Route::prefix($prefix)
    ->middleware((array) config('billing.web.middleware', ['web']))
    ->as('billing.')
    ->group(function (): void {
        if ((bool) config('billing.web.home', true)) {
            Route::view('/', 'billing::welcome')->name('home');
        }

        if ((bool) config('billing.web.print', true)) {
            Route::get('invoices/{invoice}/print', ShowInvoicePrintController::class)
                ->name('invoices.print');
        }

        if ((bool) config('billing.web.preview', true)) {
            Route::get('invoices/{invoice}', ShowInvoicePreviewController::class)
                ->name('invoices.preview');
        }

        if ((bool) config('billing.public_shares.enabled', true)) {
            Route::get('public/invoices/{token}', ShowSharedInvoiceController::class)
                ->middleware('signed')
                ->name('public.invoices.show');
        }
    });
