<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache durations in seconds
     */
    const CACHE_DURATIONS = [
        'analytics' => 300,          // 5 minutes
        'dashboard' => 180,          // 3 minutes
        'responses' => 60,           // 1 minute
        'statistics' => 600,         // 10 minutes
        'ai_metrics' => 300,         // 5 minutes
        'visualizations' => 300,     // 5 minutes
        'reports' => 3600,           // 1 hour
        'qr_codes' => 1800,          // 30 minutes
    ];

    /**
     * Get cached data or execute callback and cache result
     *
     * @param string $key Cache key
     * @param callable $callback Function to execute if cache miss
     * @param string $type Cache type (determines TTL)
     * @return mixed
     */
    public static function remember(string $key, callable $callback, string $type = 'default')
    {
        $ttl = self::CACHE_DURATIONS[$type] ?? 300;

        try {
            return Cache::remember($key, $ttl, function () use ($callback, $key) {
                Log::info("Cache miss for key: {$key}");
                return $callback();
            });
        } catch (\Exception $e) {
            Log::error("Cache error for key {$key}: " . $e->getMessage());
            return $callback(); // Fallback to direct execution
        }
    }

    /**
     * Clear specific cache keys by pattern
     *
     * @param string $pattern Pattern to match (e.g., 'analytics:*')
     * @return void
     */
    public static function clearByPattern(string $pattern): void
    {
        try {
            $keys = Cache::get('cache_keys', []);
            foreach ($keys as $key) {
                if (fnmatch($pattern, $key)) {
                    Cache::forget($key);
                    Log::info("Cleared cache for key: {$key}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error clearing cache pattern {$pattern}: " . $e->getMessage());
        }
    }

    /**
     * Clear all analytics-related caches
     *
     * @return void
     */
    public static function clearAnalyticsCache(): void
    {
        self::clearByPattern('analytics:*');
        self::clearByPattern('dashboard:*');
        self::clearByPattern('visualizations:*');
    }

    /**
     * Clear all survey response caches
     *
     * @return void
     */
    public static function clearResponseCache(): void
    {
        self::clearByPattern('responses:*');
        self::clearByPattern('survey:*');
    }

    /**
     * Track cache key for pattern clearing
     *
     * @param string $key
     * @return void
     */
    public static function trackKey(string $key): void
    {
        $keys = Cache::get('cache_keys', []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put('cache_keys', $keys, 86400); // 24 hours
        }
    }

    /**
     * Get cache statistics
     *
     * @return array
     */
    public static function getStatistics(): array
    {
        $keys = Cache::get('cache_keys', []);
        return [
            'total_keys' => count($keys),
            'keys' => $keys,
        ];
    }

    /**
     * Warm up cache with common queries
     *
     * @return void
     */
    public static function warmUp(): void
    {
        try {
            // Warm up analytics cache
            Log::info('Warming up cache...');

            // Add your warm-up logic here
            // Example: Pre-cache dashboard data

            Log::info('Cache warm-up completed');
        } catch (\Exception $e) {
            Log::error('Cache warm-up failed: ' . $e->getMessage());
        }
    }
}
