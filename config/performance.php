<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Performance Optimization Settings
    |--------------------------------------------------------------------------
    |
    | Configure caching, optimization, and performance settings
    |
    */

    'cache' => [
        'enabled' => env('CACHE_ENABLED', true),
        'default_ttl' => env('CACHE_DEFAULT_TTL', 300), // 5 minutes

        'ttl' => [
            'analytics' => env('CACHE_TTL_ANALYTICS', 300),          // 5 minutes
            'dashboard' => env('CACHE_TTL_DASHBOARD', 180),          // 3 minutes
            'responses' => env('CACHE_TTL_RESPONSES', 60),           // 1 minute
            'statistics' => env('CACHE_TTL_STATISTICS', 600),        // 10 minutes
            'ai_metrics' => env('CACHE_TTL_AI_METRICS', 300),        // 5 minutes
            'visualizations' => env('CACHE_TTL_VISUALIZATIONS', 300), // 5 minutes
            'reports' => env('CACHE_TTL_REPORTS', 3600),             // 1 hour
            'qr_codes' => env('CACHE_TTL_QR_CODES', 1800),           // 30 minutes
        ],
    ],

    'optimization' => [
        // Enable database query caching
        'query_cache' => env('QUERY_CACHE_ENABLED', true),

        // Enable view caching
        'view_cache' => env('VIEW_CACHE_ENABLED', true),

        // Enable route caching (should be false in development)
        'route_cache' => env('ROUTE_CACHE_ENABLED', false),

        // Enable config caching (should be false in development)
        'config_cache' => env('CONFIG_CACHE_ENABLED', false),

        // Eager load relationships
        'eager_loading' => env('EAGER_LOADING_ENABLED', true),

        // Compress responses
        'response_compression' => env('RESPONSE_COMPRESSION_ENABLED', true),
    ],

    'database' => [
        // Use database indexes
        'use_indexes' => env('DB_USE_INDEXES', true),

        // Connection pooling
        'connection_pooling' => env('DB_CONNECTION_POOLING', true),

        // Persistent connections
        'persistent' => env('DB_PERSISTENT', false),
    ],

    'assets' => [
        // Enable asset minification
        'minify' => env('ASSETS_MINIFY', true),

        // Enable asset versioning/cache busting
        'versioning' => env('ASSETS_VERSIONING', true),

        // CDN URL for assets
        'cdn_url' => env('ASSETS_CDN_URL', null),
    ],

    'api' => [
        // Rate limiting
        'rate_limit' => [
            'enabled' => env('API_RATE_LIMIT_ENABLED', true),
            'max_attempts' => env('API_RATE_LIMIT_MAX', 60),
            'decay_minutes' => env('API_RATE_LIMIT_DECAY', 1),
        ],

        // API response caching
        'cache_responses' => env('API_CACHE_RESPONSES', true),
    ],

];
