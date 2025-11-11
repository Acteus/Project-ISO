<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware
 * 
 * Adds security headers to all HTTP responses to protect against common attacks:
 * - XSS Protection
 * - Clickjacking Protection
 * - MIME Type Sniffing Protection
 * - HTTPS Enforcement (HSTS)
 * - Content Security Policy (CSP)
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add security headers to HTTP responses (not redirects or exceptions)
        if ($response instanceof Response) {
            // X-Content-Type-Options: Prevents MIME type sniffing
            $response->headers->set('X-Content-Type-Options', 'nosniff');

            // X-Frame-Options: Prevents clickjacking attacks
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

            // X-XSS-Protection: Enables browser XSS filter (legacy but still useful)
            $response->headers->set('X-XSS-Protection', '1; mode=block');

            // Strict-Transport-Security: Force HTTPS connections (only on HTTPS)
            if ($request->secure() || $request->header('X-Forwarded-Proto') === 'https') {
                $hstsMaxAge = env('HSTS_MAX_AGE', 31536000); // 1 year default
                $response->headers->set('Strict-Transport-Security', "max-age={$hstsMaxAge}; includeSubDomains; preload");
            }

            // Referrer-Policy: Control referrer information
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

            // Permissions-Policy: Restrict browser features
            $response->headers->set('Permissions-Policy', 
                'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), speaker=()'
            );

            // Content-Security-Policy: Prevent XSS and injection attacks
            // Note: Adjust CSP based on your application's needs
            $csp = $this->buildContentSecurityPolicy($request);
            if ($csp) {
                $response->headers->set('Content-Security-Policy', $csp);
            }

            // Remove server information
            $response->headers->remove('Server');
            $response->headers->remove('X-Powered-By');
        }

        return $response;
    }

    /**
     * Build Content Security Policy header
     * 
     * @param Request $request
     * @return string|null
     */
    protected function buildContentSecurityPolicy(Request $request): ?string
    {
        // For API routes, use a more permissive CSP
        if ($request->is('api/*')) {
            return "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' " . env('APP_URL', '');
        }

        // For web routes, use stricter CSP
        $appUrl = parse_url(env('APP_URL', ''), PHP_URL_HOST);
        return "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' " . ($appUrl ? "https://{$appUrl}" : '');
    }
}

