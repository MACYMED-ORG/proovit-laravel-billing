<?php

declare(strict_types=1);

use Proovit\Billing\Billing;

it('reads arbitrary config keys through the helper', function (): void {
    $billing = app(Billing::class);

    expect($billing->config('pdf.paper'))->toBe('a4');
    expect($billing->config('invoice.default_due_days'))->toBe(30);
});
