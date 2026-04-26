<?php
namespace Proovit\Billing\Http;

use Dedoc\Scramble\Generator;
use Dedoc\Scramble\Scramble;

final class BillingDocsUiAction
{
    public function __invoke(Generator $generator)
    {
        $apiName = (string) config('billing.docs.name', 'billing');

        $config = Scramble::registerApi($apiName, [
            'api_path' => config('billing.docs.api_prefix', 'api/billing'),
            'api_domain' => config('billing.docs.domain'),
            'middleware' => (array) config('billing.docs.middleware', ['web']),
        ]);

        return view('scramble::docs', [
            'spec' => $generator($config),
            'config' => $config,
        ]);
    }
}