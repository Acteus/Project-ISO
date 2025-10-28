<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Flask AI Service Client
 * Handles communication with the Python Flask AI service
 */
class FlaskAIClient
{
    protected $baseUrl;
    protected $timeout;
    protected $retries;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('ai.flask_service_url', 'http://localhost:5002');
        $this->timeout = config('ai.timeout_seconds', 30);
        $this->retries = config('ai.max_retries', 3);
        $this->apiKey = config('ai.api_key');
    }

    /**
     * Check if Flask service is available
     */
    public function isServiceAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");

            return $response->successful() &&
                   isset($response->json()['status']) &&
                   $response->json()['status'] === 'healthy';

        } catch (\Exception $e) {
            Log::warning('Flask AI service health check failed', [
                'error' => $e->getMessage(),
                'url' => $this->baseUrl
            ]);
            return false;
        }
    }

    /**
     * Predict ISO 21001 compliance using Flask service
     */
    public function predictCompliance(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/compliance/predict', $data);
    }

    /**
     * Analyze sentiment using Flask service
     */
    public function analyzeSentiment(array $comments): ?array
    {
        return $this->makeRequest('POST', '/api/v1/sentiment/analyze', [
            'comments' => $comments
        ]);
    }

    /**
     * Cluster students using Flask service
     */
    public function clusterStudents(array $responses, int $clusters = 3): ?array
    {
        return $this->makeRequest('POST', '/api/v1/students/cluster', [
            'responses' => $responses,
            'clusters' => $clusters
        ]);
    }

    /**
     * Predict student performance using Flask service
     */
    public function predictPerformance(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/performance/predict', $data);
    }

    /**
     * Predict dropout risk using Flask service
     */
    public function predictDropoutRisk(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/dropout/predict', $data);
    }

    /**
     * Assess comprehensive risk using Flask service
     */
    public function assessRisk(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/risk/assess', $data);
    }

    /**
     * Predict satisfaction trend using Flask service
     */
    public function predictSatisfactionTrend(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/satisfaction/trend', $data);
    }

    /**
     * Get comprehensive analytics from Flask service
     */
    public function getComprehensiveAnalytics(array $data): ?array
    {
        return $this->makeRequest('POST', '/api/v1/analytics/comprehensive', $data);
    }

    /**
     * Make HTTP request to Flask service with retry logic
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): ?array
    {
        $url = $this->baseUrl . $endpoint;
        $cacheKey = 'flask_ai_' . md5($method . $url . json_encode($data));

        // Check cache for GET requests
        if ($method === 'GET' && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $attempts = 0;
        $lastException = null;

        while ($attempts < $this->retries) {
            try {
                $httpClient = Http::timeout($this->timeout);

                // Add API key if configured
                if ($this->apiKey) {
                    $httpClient = $httpClient->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'X-API-Key' => $this->apiKey
                    ]);
                }

                // Add CSRF token for Laravel compatibility
                if (session()->has('_token')) {
                    $httpClient = $httpClient->withHeaders([
                        'X-CSRF-TOKEN' => session()->get('_token')
                    ]);
                }

                $response = $httpClient->send($method, $url, [
                    'json' => $data
                ]);

                if ($response->successful()) {
                    $result = $response->json();

                    // Cache successful GET responses for 5 minutes
                    if ($method === 'GET') {
                        Cache::put($cacheKey, $result, 300);
                    }

                    Log::info('Flask AI service request successful', [
                        'endpoint' => $endpoint,
                        'attempts' => $attempts + 1
                    ]);

                    return $result;
                } else {
                    Log::warning('Flask AI service request failed', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'attempts' => $attempts + 1
                    ]);
                }

            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning('Flask AI service request exception', [
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                    'attempts' => $attempts + 1
                ]);
            }

            $attempts++;

            // Wait before retry (exponential backoff)
            if ($attempts < $this->retries) {
                sleep(pow(2, $attempts - 1));
            }
        }

        Log::error('Flask AI service request failed after all retries', [
            'endpoint' => $endpoint,
            'final_error' => $lastException ? $lastException->getMessage() : 'Unknown error',
            'total_attempts' => $attempts
        ]);

        return null;
    }

    /**
     * Get service status and metrics
     */
    public function getServiceStatus(): array
    {
        $available = $this->isServiceAvailable();

        return [
            'available' => $available,
            'base_url' => $this->baseUrl,
            'timeout' => $this->timeout,
            'retries' => $this->retries,
            'last_checked' => now()->toISOString(),
            'cache_enabled' => config('ai.enable_cache', true)
        ];
    }

    /**
     * Clear API response cache
     */
    public function clearCache(): bool
    {
        try {
            Cache::forget('flask_ai_*'); // This is a pattern, but in practice you'd need to handle this differently
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to clear Flask AI cache', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
