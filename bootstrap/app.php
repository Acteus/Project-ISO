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

        $middleware->validateCsrfTokens(except: [
            'api/survey/submit',
        ]);

        // Register cache response middleware
        $middleware->alias([
            'cache.response' => \App\Http\Middleware\CacheResponse::class,
            'cache.api' => \App\Http\Middleware\CacheApiResponse::class,
            'query.logging' => \App\Http\Middleware\QueryLoggingMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
        ]);

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
