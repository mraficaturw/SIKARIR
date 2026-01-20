<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * 
     * Add security headers to prevent common attacks:
     * - Clickjacking (X-Frame-Options)
     * - MIME sniffing (X-Content-Type-Options)
     * - XSS (X-XSS-Protection)
     * - Information leakage (Referrer-Policy)
     * - Feature abuse (Permissions-Policy)
     * - Various attacks (Content-Security-Policy)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking - only allow framing from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable browser XSS filter (legacy but still useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information leakage
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict browser features/permissions
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy - restricts sources for various content types
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' wss: https:",
            "frame-ancestors 'self'",
            "form-action 'self'",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
