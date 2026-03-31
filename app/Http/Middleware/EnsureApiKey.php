<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = (string) env('API_ACCESS_KEY', '');
        $providedKey = (string) $request->header('X-API-KEY', '');

        if ($expectedKey === '' || ! hash_equals($expectedKey, $providedKey)) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
