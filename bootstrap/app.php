<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies for Cloudflare tunnel
        $middleware->trustProxies(at: '*', headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
            \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO);

        // CSRF Protection Configuration
        // SECURITY FIX: Exclude API routes from CSRF protection as they use API authentication
        // Note: api/survey/submit is excluded for external form submissions
        // However, it's protected by rate limiting and input validation/sanitization
        // Other API routes are protected by authentication (Sanctum or session-based)
        $middleware->validateCsrfTokens(except: [
            'api/survey/submit', // Public endpoint for survey submissions (protected by rate limiting)
            'api/admin/login', // API authentication endpoint (protected by rate limiting)
            'api/admin/logout', // API authentication endpoint (protected by auth:sanctum)
            'api/ai/*', // AI endpoints (protected by authentication and rate limiting)
            'api/export/*', // Export endpoints (protected by authentication)
            'api/visualization/*', // Visualization endpoints (protected by authentication)
            'api/visualizations/*', // Advanced visualization endpoints (protected by authentication)
        ]);

        // Register cache response middleware
        $middleware->alias([
            'cache.response' => \App\Http\Middleware\CacheResponse::class,
            'cache.api' => \App\Http\Middleware\CacheApiResponse::class,
            'query.logging' => \App\Http\Middleware\QueryLoggingMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
            'api.key' => \App\Http\Middleware\ApiKeyAuthentication::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // SECURITY FIX: Add security headers to all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Apply audit middleware to API routes for automatic access logging
        $middleware->appendToGroup('api', \App\Http\Middleware\AuditMiddleware::class);

        // Apply query logging middleware only when explicitly enabled
        // Disabled by default in production for Cloudways compatibility
        if (env('QUERY_LOGGING_ENABLED', false) || in_array(env('APP_ENV', 'production'), ['local', 'testing'])) {
            $middleware->appendToGroup('web', \App\Http\Middleware\QueryLoggingMiddleware::class);
            $middleware->appendToGroup('api', \App\Http\Middleware\QueryLoggingMiddleware::class);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
