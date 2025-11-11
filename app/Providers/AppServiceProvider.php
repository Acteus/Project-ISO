<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Models\SurveyResponse;
use App\Observers\SurveyResponseObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Smart configuration for multi-environment support:
     *
     * LOCAL DEVELOPMENT (.env):
     * - SESSION_DRIVER=database
     * - CACHE_STORE=database
     * - Uses database/file storage (no Redis required)
     *
     * CLOUDWAYS PRODUCTION (.env.production):
     * - SESSION_DRIVER=redis
     * - CACHE_STORE=redis
     * - Auto-detects Cloudways and uses Redis for optimal performance
     * - Falls back to database if Redis is unavailable
     *
     * This provider automatically:
     * - Respects .env configuration
     * - Tests Redis connectivity before using it
     * - Provides intelligent fallbacks
     * - Logs configuration status for debugging
     */
    public function boot(): void
    {
        // Smart configuration for both Cloudways (production) and local environments
        // Respects .env settings while providing intelligent fallbacks

        $sessionDriver = env('SESSION_DRIVER', 'database');
        $cacheStore = env('CACHE_STORE', 'database');
        $isProduction = app()->environment('production');

        // Auto-detect Cloudways environment
        $isCloudways = $isProduction && (
            str_contains(env('DB_HOST', ''), 'cloudways') ||
            str_contains(env('APP_URL', ''), 'cloudwaysapps.com') ||
            env('DB_DATABASE') === 'kxvekkgpkz' // Your Cloudways DB name
        );

        // Configure Redis if explicitly requested OR if on Cloudways with Redis available
        if ($sessionDriver === 'redis' || $cacheStore === 'redis' || $isCloudways) {
            if (env('REDIS_HOST') && env('REDIS_PORT')) {
                try {
                    // Test Redis connection
                    $redis = app('redis');
                    $redis->connection()->ping();

                    // Configure Redis for sessions and cache
                    if ($sessionDriver === 'redis' || $isCloudways) {
                        config(['session.driver' => 'redis']);
                        config(['session.store' => 'cache']);
                    }
                    if ($cacheStore === 'redis' || $isCloudways) {
                        config(['cache.default' => 'redis']);
                    }

                    Log::info('Redis configured successfully', [
                        'environment' => app()->environment(),
                        'is_cloudways' => $isCloudways,
                    ]);
                } catch (\Exception $e) {
                    // Gracefully fall back to file/database for local, database for production
                    $fallbackCache = $isProduction ? 'database' : 'file';
                    $fallbackSession = $isProduction ? 'database' : 'file';

                    config(['session.driver' => $fallbackSession]);
                    config(['cache.default' => $fallbackCache]);

                    Log::warning('Redis unavailable, using fallback storage', [
                        'error' => $e->getMessage(),
                        'cache_fallback' => $fallbackCache,
                        'session_fallback' => $fallbackSession,
                    ]);
                }
            }
        }
        // For local development without Redis, use .env configuration (database/file)

        // Register model observers for automatic cache clearing
        SurveyResponse::observe(SurveyResponseObserver::class);

        // Enable query logging in development for performance monitoring
        if (config('app.debug')) {
            DB::listen(function ($query) {
                // Log slow queries (> 100ms)
                if ($query->time > 100) {
                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms'
                    ]);
                }
            });
        }

        // Share common data with all views if needed
        View::composer('*', function ($view) {
            // Add any global view data here
        });

        // Schedule automated compliance reporting (ISO 21001 Clause 8.2.4)
        Schedule::command('compliance:generate-report --period=monthly --send-email')
            ->monthlyOn(1, '08:00')
            ->timezone('Asia/Manila')
            ->description('Generate monthly ISO 21001 compliance report');

        // Also run weekly compliance reports for monitoring
        Schedule::command('compliance:generate-report --period=weekly')
            ->weekly()
            ->mondays()
            ->at('08:00')
            ->timezone('Asia/Manila')
            ->description('Generate weekly ISO 21001 compliance report');
    }
}
