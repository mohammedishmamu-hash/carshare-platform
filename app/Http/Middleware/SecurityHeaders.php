<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent browsers from guessing the content type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent the app from being embedded in an iframe (clickjacking)
        $response->headers->set('X-Frame-Options', 'DENY');

        // Control how much referrer info is sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}