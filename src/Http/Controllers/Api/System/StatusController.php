<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\System;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

#[Group('System', description: 'Package health and smoke-test endpoints.')]
final class StatusController extends Controller
{
    #[Endpoint(
        operationId: 'systemStatus',
        title: 'Package status',
        description: 'Return the basic package health payload used by smoke tests and monitoring.'
    )]
    #[Response(
        status: 200,
        type: 'array{data: array{loaded: bool, locale: string, version: string}}',
        description: 'Basic package status payload.'
    )]
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'loaded' => true,
                'locale' => app()->getLocale(),
                'version' => config('billing.api.version', 'v1'),
            ],
        ]);
    }
}
