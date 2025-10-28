<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI services including Flask integration and fallback settings
    |
    */

    'flask_service_url' => env('FLASK_AI_SERVICE_URL', 'http://localhost:5001'),

    'api_key' => env('FLASK_AI_API_KEY'),

    'timeout_seconds' => env('AI_TIMEOUT_SECONDS', 30),

    'max_retries' => env('AI_MAX_RETRIES', 3),

    'enable_cache' => env('AI_ENABLE_CACHE', true),

    'cache_ttl' => env('AI_CACHE_TTL', 300), // 5 minutes

    'fallback_to_php' => env('AI_FALLBACK_TO_PHP', true),

    'service_check_interval' => env('AI_SERVICE_CHECK_INTERVAL', 60), // seconds

    /*
    |--------------------------------------------------------------------------
    | Model Configuration
    |--------------------------------------------------------------------------
    */

    'models' => [
        'compliance_predictor' => [
            'enabled' => env('AI_COMPLIANCE_MODEL_ENABLED', true),
            'min_confidence' => env('AI_COMPLIANCE_MIN_CONFIDENCE', 0.5),
        ],

        'sentiment_analyzer' => [
            'enabled' => env('AI_SENTIMENT_MODEL_ENABLED', true),
            'min_confidence' => env('AI_SENTIMENT_MIN_CONFIDENCE', 0.6),
        ],

        'student_clusterer' => [
            'enabled' => env('AI_CLUSTER_MODEL_ENABLED', true),
            'default_clusters' => env('AI_DEFAULT_CLUSTERS', 3),
            'max_clusters' => env('AI_MAX_CLUSTERS', 8),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */

    'performance' => [
        'batch_size' => env('AI_BATCH_SIZE', 50),
        'max_concurrent_requests' => env('AI_MAX_CONCURRENT_REQUESTS', 10),
        'circuit_breaker_threshold' => env('AI_CIRCUIT_BREAKER_THRESHOLD', 5),
        'circuit_breaker_timeout' => env('AI_CIRCUIT_BREAKER_TIMEOUT', 300), // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring and Logging
    |--------------------------------------------------------------------------
    */

    'monitoring' => [
        'enable_metrics' => env('AI_ENABLE_METRICS', true),
        'log_requests' => env('AI_LOG_REQUESTS', true),
        'log_errors' => env('AI_LOG_ERRORS', true),
        'performance_tracking' => env('AI_PERFORMANCE_TRACKING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | ISO 21001 Specific Settings
    |--------------------------------------------------------------------------
    */

    'iso_21001' => [
        'compliance_weights' => [
            'learner_needs_index' => 0.15,
            'satisfaction_score' => 0.25,
            'success_index' => 0.20,
            'safety_index' => 0.20,
            'wellbeing_index' => 0.15,
            'overall_satisfaction' => 0.05,
        ],

        'risk_thresholds' => [
            'high_compliance' => 4.2,
            'moderate_compliance' => 3.5,
            'low_compliance' => 3.0,
        ],

        'sentiment_thresholds' => [
            'high_satisfaction' => 70,
            'moderate_satisfaction' => 50,
            'low_satisfaction' => 30,
        ],
    ],
];
