<?php

declare(strict_types=1);

namespace Proovit\Billing\Http\Controllers\Api\System;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

#[Group('System')]
final class StatusController extends Controller
{
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
