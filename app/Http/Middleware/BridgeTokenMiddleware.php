<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shared secret for PMS ↔ HIMS machine calls (Bearer token).
 */
class BridgeTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.pms.token', env('PMS_BRIDGE_TOKEN', ''));
        if ($expected === '') {
            return response()->json(['message' => 'Bridge token not configured on HIMS'], 503);
        }

        $auth = (string) $request->header('Authorization', '');
        $token = '';
        if (preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
            $token = trim($m[1]);
        }
        if ($token === '') {
            $token = trim((string) $request->header('X-Bridge-Token', ''));
        }

        if ($token === '' || ! hash_equals($expected, $token)) {
            return response()->json(['message' => 'Unauthorized bridge token'], 401);
        }

        return $next($request);
    }
}
