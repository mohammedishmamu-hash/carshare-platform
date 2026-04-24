<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds security-related HTTP response headers to every response.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; " .
            "style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; " .
            "font-src 'self' https://cdn.jsdelivr.net; " .
            "img-src 'self' data:; " .
            "connect-src 'self';"
        );

        return $response;
    }
}