<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Services\Integrations\IntegrationHmacService;

class HmacMiddleware
{

    public function __construct(protected IntegrationHmacService $integrationHmacService) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isValid = $this->integrationHmacService->validate(
            receivedHmac: $request->header('X-Token'),
            timestamp: $request->header('X-Timestamp')
        );

        if (!$isValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid HMAC'
            ], 401);
        }

        return $next($request);
    }
}
