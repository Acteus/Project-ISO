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
        // Generate nonce BEFORE processing the request so it's available to views
        // Only generate nonce for routes that might render views (not API routes or static files)
        $path = $request->path();
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $staticExtensions = ['css', 'js', 'xml', 'json', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];

        $needsNonce = !$request->is('api/*') &&
                     !in_array(strtolower($extension), $staticExtensions);

        if ($needsNonce) {
            $nonce = base64_encode(random_bytes(16));
            $request->attributes->set('csp-nonce', $nonce);

            // Make nonce available to all views via view()->share()
            if (app()->bound('view')) {
                view()->share('cspNonce', $nonce);
            }
        }

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
        // Get nonce from request (already generated in handle method if needed)
        $nonce = $request->attributes->get('csp-nonce', '');

        // Parse APP_URL for CSP directives
        $appUrl = env('APP_URL', '');
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        $appScheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'https';
        $appOrigin = $appHost ? "{$appScheme}://{$appHost}" : "'self'";

        // Determine if this is an API route, static file, or web route with views
        $isApiRoute = $request->is('api/*');
        $path = $request->path();
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $staticExtensions = ['css', 'js', 'xml', 'json', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        $isStaticFile = in_array(strtolower($extension), $staticExtensions);

        // Build CSP directives based on route type
        if ($isApiRoute || $isStaticFile) {
            // For API routes and static files, use stricter CSP without nonces
            // (no inline scripts/styles needed)
            $directives = [
                "default-src 'self'",
                "script-src 'self'",
                "style-src 'self'",
                "img-src 'self' data: https:",
                "font-src 'self' data:",
                "connect-src 'self' {$appOrigin}",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "object-src 'none'",
                "frame-src 'none'",
                "media-src 'self'",
                "worker-src 'none'",
                "manifest-src 'self'",
            ];
        } else {
            // For web routes with views
            // TEMPORARY: Allow 'unsafe-inline' for styles to fix UI issues
            // TODO: Remove 'unsafe-inline' once all inline styles are moved to CSS classes
            // For scripts, we use nonces to avoid 'unsafe-inline' and 'unsafe-eval'
            // Allow Chart.js CDN for analytics dashboard
            if (!empty($nonce)) {
                $directives = [
                    "default-src 'self'",
                    // Allow scripts from trusted sources
                    // NOTE: Cloudflare Insights may inject scripts with integrity attributes that don't match.
                    // This is a Cloudflare-side issue. If you see integrity hash errors, either:
                    // 1. Disable Cloudflare Web Analytics in Cloudflare dashboard
                    // 2. Configure Cloudflare to not add integrity attributes
                    // 3. Contact Cloudflare support to fix the hash
                    "script-src 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://static.cloudflareinsights.com",
                    "style-src 'self' 'unsafe-inline'", // TEMPORARY: Allow unsafe-inline for styles
                    "img-src 'self' data: https:",
                    "font-src 'self' data: https://cdn.jsdelivr.net",
                    "connect-src 'self' {$appOrigin} https://cloudflareinsights.com", // Allow Cloudflare Insights beacon
                    "frame-ancestors 'self'",
                    "base-uri 'self'",
                    "form-action 'self'",
                    "object-src 'none'",
                    "frame-src 'none'",
                    "media-src 'self'",
                    "worker-src 'none'",
                    "manifest-src 'self'",
                ];
            } else {
                // Fallback: if no nonce, still set CSP
                $directives = [
                    "default-src 'self'",
                    "script-src 'self' https://cdn.jsdelivr.net https://static.cloudflareinsights.com", // Allow Chart.js CDN and Cloudflare Insights
                    "style-src 'self' 'unsafe-inline'", // TEMPORARY: Allow unsafe-inline for styles
                    "img-src 'self' data: https:",
                    "font-src 'self' data: https://cdn.jsdelivr.net",
                    "connect-src 'self' {$appOrigin} https://cloudflareinsights.com", // Allow Cloudflare Insights beacon
                    "frame-ancestors 'self'",
                    "base-uri 'self'",
                    "form-action 'self'",
                    "object-src 'none'",
                    "frame-src 'none'",
                    "media-src 'self'",
                    "worker-src 'none'",
                    "manifest-src 'self'",
                ];
            }
        }

        // Add upgrade-insecure-requests only for HTTPS
        if ($request->secure() || $request->header('X-Forwarded-Proto') === 'https') {
            $directives[] = "upgrade-insecure-requests";
        }

        return implode('; ', $directives);
    }

    /**
     * Get the CSP nonce for the current request
     *
     * @param Request $request
     * @return string
     */
    public static function getNonce(Request $request): string
    {
        return $request->attributes->get('csp-nonce', '');
    }
}

