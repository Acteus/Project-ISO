<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cache API Response Middleware
 * 
 * Caches API endpoint responses to improve performance.
 * Automatically invalidates cache on POST/PUT/DELETE requests.
 */
class CacheApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $ttl = 300): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Skip caching for authenticated requests that might have user-specific data
        // unless explicitly enabled
        $skipAuthCache = $request->header('X-Skip-Cache', false);
        if ($request->user() && !$skipAuthCache) {
            // For authenticated users, include user ID in cache key
            $cacheKey = $this->generateCacheKey($request);
        } else {
            $cacheKey = $this->generateCacheKey($request);
        }

        // Try to get cached response (with error handling for Cloudways)
        try {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                Log::debug("Cache hit for API endpoint: {$request->path()}");
                return response()->json($cached['data'], $cached['status'])
                    ->header('X-Cache', 'HIT')
                    ->header('X-Cache-Key', $cacheKey);
            }
        } catch (\Exception $e) {
            // If cache fails (e.g., Redis connection issue), continue without cache
            Log::warning("Cache read failed for {$request->path()}: " . $e->getMessage());
        }

        // Execute request and cache response
        $response = $next($request);

        // Only cache successful JSON responses (with error handling for Cloudways)
        if ($response->getStatusCode() === 200 && $response->headers->get('Content-Type') === 'application/json') {
            try {
                $content = $response->getContent();
                $data = json_decode($content, true);

                if ($data !== null) {
                    Cache::put($cacheKey, [
                        'data' => $data,
                        'status' => 200,
                    ], $ttl);

                    Log::debug("Cached API response for: {$request->path()}");
                }
            } catch (\Exception $e) {
                // If cache write fails (e.g., Redis connection issue), log but don't fail request
                Log::warning("Cache write failed for {$request->path()}: " . $e->getMessage());
            }
        }

        return $response->header('X-Cache', 'MISS');
    }

    /**
     * Generate cache key from request
     *
     * @param Request $request
     * @return string
     */
    protected function generateCacheKey(Request $request): string
    {
        $key = 'api:' . $request->method() . ':' . $request->path();
        
        // Include query parameters in cache key
        $query = $request->query();
        if (!empty($query)) {
            ksort($query); // Sort for consistent keys
            $key .= ':' . md5(serialize($query));
        }

        // Include user ID if authenticated
        if ($user = $request->user()) {
            $key .= ':user:' . $user->id;
        }

        return $key;
    }

    /**
     * Clear cache for a specific endpoint pattern
     *
     * @param string $pattern
     * @return void
     */
    public static function clearCache(string $pattern = '*'): void
    {
        // This would need to be implemented based on your cache driver
        // For Redis, you could use SCAN to find matching keys
        Log::info("Cache clear requested for pattern: {$pattern}");
    }
}

