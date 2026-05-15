<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidSanctumToken
{
    public function handle(Request $request, Closure $next): Response
    {
       
        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'status' => false,
                'message' => 'Token is expired, revoked, or invalid. Please log in again.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
