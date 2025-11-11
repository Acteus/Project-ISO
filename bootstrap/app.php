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
        // SECURITY FIX: CSRF protection is applied to all web routes by default
        // API routes using Sanctum tokens (stateless) don't need CSRF protection
        // State-changing API routes that use web middleware should have CSRF protection
        //
        // Excluded routes:
        // - api/survey/submit: Public endpoint for external form submissions (protected by rate limiting)
        // - api/admin/login: Stateless API authentication (uses Sanctum tokens, protected by rate limiting)
        // - api/admin/logout: Stateless API authentication (uses Sanctum tokens, protected by auth:sanctum)
        // - api/ai/*, api/export/*, api/visualization/*, api/visualizations/*:
        //   All use Sanctum authentication (stateless tokens, no CSRF needed)
        //
        // Note: All other API routes use Sanctum authentication which is stateless and doesn't require CSRF.
        // State-changing routes (POST, PUT, DELETE, PATCH) are protected by:
        // 1. Sanctum token authentication (stateless, no CSRF needed)
        // 2. Rate limiting where appropriate
        // 3. Input validation and sanitization
        $middleware->validateCsrfTokens(except: [
            'api/survey/submit', // Public endpoint for survey submissions (protected by rate limiting and validation)
            'api/admin/login', // Stateless API authentication endpoint (protected by rate limiting)
            'api/admin/logout', // Stateless API authentication endpoint (protected by auth:sanctum)
            'api/ai/*', // AI endpoints (protected by Sanctum authentication and rate limiting)
            'api/export/*', // Export endpoints (protected by Sanctum authentication)
            'api/visualization/*', // Visualization endpoints (protected by Sanctum authentication)
            'api/visualizations/*', // Advanced visualization endpoints (protected by Sanctum authentication)
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
