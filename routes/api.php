<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Proovit\Billing\Http\Controllers\Api\Customers\DeleteCustomerController;
use Proovit\Billing\Http\Controllers\Api\Quotes\DeleteQuoteController;
use Proovit\Billing\Http\Controllers\Api\Invoices\FinalizeInvoiceController;
use Proovit\Billing\Http\Controllers\Api\Invoices\GenerateInvoiceShareLinkController;
use Proovit\Billing\Http\Controllers\Api\Quotes\ConvertQuoteToInvoiceController;
use Proovit\Billing\Http\Controllers\Api\Customers\ListCustomersController;
use Proovit\Billing\Http\Controllers\Api\Quotes\ListQuotesController;
use Proovit\Billing\Http\Controllers\Api\Invoices\ListInvoicesController;
use Proovit\Billing\Http\Controllers\Api\Customers\ShowCustomerController;
use Proovit\Billing\Http\Controllers\Api\Quotes\ShowQuoteController;
use Proovit\Billing\Http\Controllers\Api\Invoices\ShowInvoiceController;
use Proovit\Billing\Http\Controllers\Api\System\StatusController;
use Proovit\Billing\Http\Controllers\Api\Customers\StoreCustomerController;
use Proovit\Billing\Http\Controllers\Api\Invoices\StoreCreditNoteController;
use Proovit\Billing\Http\Controllers\Api\Invoices\StoreInvoiceController;
use Proovit\Billing\Http\Controllers\Api\Invoices\StorePaymentController;
use Proovit\Billing\Http\Controllers\Api\Quotes\StoreQuoteController;
use Proovit\Billing\Http\Controllers\Api\Customers\UpdateCustomerController;
use Proovit\Billing\Http\Controllers\Api\Invoices\UpdateDraftInvoiceController;
use Proovit\Billing\Http\Controllers\Api\Quotes\UpdateQuoteController;

$prefix = trim(sprintf(
    '%s/%s',
    config('billing.api.prefix', 'api/billing'),
    config('billing.api.version', 'v1'),
), '/');

$middleware = array_values(array_filter(array_merge(
    (array) config('billing.api.middleware', ['api']),
    (array) config('billing.api.auth_middleware', [])
)));

Route::prefix($prefix)
    ->middleware($middleware)
    ->as('billing.api.')
    ->group(function (): void {
        Route::get('status', StatusController::class)->name('status');

        Route::get('customers', ListCustomersController::class)->name('customers.index');
        Route::post('customers', StoreCustomerController::class)->name('customers.store');
        Route::get('customers/{customer}', ShowCustomerController::class)->name('customers.show');
        Route::patch('customers/{customer}', UpdateCustomerController::class)->name('customers.update');
        Route::delete('customers/{customer}', DeleteCustomerController::class)->name('customers.destroy');

        Route::get('invoices', ListInvoicesController::class)->name('invoices.index');
        Route::post('invoices', StoreInvoiceController::class)->name('invoices.store');
        Route::get('invoices/{invoice}', ShowInvoiceController::class)->name('invoices.show');
        Route::patch('invoices/{invoice}', UpdateDraftInvoiceController::class)->name('invoices.update');
        Route::post('invoices/{invoice}/finalize', FinalizeInvoiceController::class)->name('invoices.finalize');
        Route::post('invoices/{invoice}/payments', StorePaymentController::class)->name('invoices.payments.store');
        Route::post('invoices/{invoice}/credit-notes', StoreCreditNoteController::class)->name('invoices.credit-notes.store');
        Route::post('invoices/{invoice}/share-link', GenerateInvoiceShareLinkController::class)->name('invoices.share-link');
        Route::get('quotes', ListQuotesController::class)->name('quotes.index');
        Route::post('quotes', StoreQuoteController::class)->name('quotes.store');
        Route::get('quotes/{quote}', ShowQuoteController::class)->name('quotes.show');
        Route::patch('quotes/{quote}', UpdateQuoteController::class)->name('quotes.update');
        Route::delete('quotes/{quote}', DeleteQuoteController::class)->name('quotes.destroy');
        Route::post('quotes/{quote}/convert', ConvertQuoteToInvoiceController::class)->name('quotes.convert');
    });
