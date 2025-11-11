<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * API Key Authentication Middleware
 * 
 * Provides API key-based authentication for service-to-service API calls.
 * This is an alternative to Sanctum tokens for programmatic access.
 * 
 * Usage:
 * - Set LARAVEL_API_KEY in .env file
 * - Send API key in X-API-Key header or as api_key query parameter
 * - Apply middleware to routes that need API key authentication
 */
class ApiKeyAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get API key from environment
        $validApiKey = config('app.api_key');
        
        // If no API key is configured, allow the request (for backward compatibility)
        if (empty($validApiKey)) {
            Log::warning('API key authentication middleware used but no API key configured');
            return $next($request);
        }
        
        // Get API key from request
        $apiKey = $request->header('X-API-Key') 
               ?? $request->header('Authorization') 
               ?? $request->query('api_key');
        
        // Extract Bearer token if present
        if ($apiKey && strpos($apiKey, 'Bearer ') === 0) {
            $apiKey = substr($apiKey, 7);
        }
        
        // Validate API key
        if (empty($apiKey) || !hash_equals($validApiKey, $apiKey)) {
            Log::warning('API key authentication failed', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'has_key' => !empty($apiKey),
            ]);
            
            return response()->json([
                'message' => 'Unauthorized. Invalid or missing API key.',
                'error' => 'API_KEY_INVALID'
            ], 401);
        }
        
        // Log successful API key authentication
        Log::info('API key authentication successful', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);
        
        return $next($request);
    }
}

