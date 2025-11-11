<?php

namespace App\Services;

use App\Models\PerformanceMetric;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Performance Monitoring Service
 * 
 * Tracks and analyzes performance metrics for:
 * - AI service integration (Flask API calls)
 * - Analytics queries (database query performance)
 * - Application bottlenecks
 */
class PerformanceMonitoringService
{
    /**
     * Track AI service call performance
     */
    public function trackAIServiceCall(
        string $service,
        string $endpoint,
        float $duration,
        bool $success,
        ?string $error = null,
        ?array $metadata = null
    ): void {
        try {
            $this->recordMetric([
                'type' => 'ai_service',
                'category' => $service,
                'operation' => $endpoint,
                'duration_ms' => $duration * 1000, // Convert to milliseconds
                'success' => $success,
                'error' => $error,
                'metadata' => $metadata ? json_encode($metadata) : null,
            ]);

            // Log slow AI calls (> 2 seconds)
            if ($duration > 2.0) {
                Log::warning('Slow AI service call detected', [
                    'service' => $service,
                    'endpoint' => $endpoint,
                    'duration' => round($duration, 2) . 's',
                    'success' => $success,
                ]);
            }

            // Track failures
            if (!$success) {
                Log::error('AI service call failed', [
                    'service' => $service,
                    'endpoint' => $endpoint,
                    'error' => $error,
                    'duration' => round($duration, 2) . 's',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to track AI service performance', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Track analytics query performance
     */
    public function trackAnalyticsQuery(
        string $queryType,
        float $duration,
        int $rowsAffected = 0,
        ?string $query = null,
        ?array $metadata = null
    ): void {
        try {
            $this->recordMetric([
                'type' => 'analytics_query',
                'category' => 'analytics',
                'operation' => $queryType,
                'duration_ms' => $duration * 1000, // Convert to milliseconds
                'success' => true,
                'rows_affected' => $rowsAffected,
                'query' => $query ? substr($query, 0, 1000) : null, // Limit query length
                'metadata' => $metadata ? json_encode($metadata) : null,
            ]);

            // Log slow queries (> 1 second)
            if ($duration > 1.0) {
                Log::warning('Slow analytics query detected', [
                    'query_type' => $queryType,
                    'duration' => round($duration, 2) . 's',
                    'rows' => $rowsAffected,
                ]);
            }

            // Log very slow queries (> 5 seconds)
            if ($duration > 5.0) {
                Log::error('Very slow analytics query detected', [
                    'query_type' => $queryType,
                    'duration' => round($duration, 2) . 's',
                    'rows' => $rowsAffected,
                    'query' => $query,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to track analytics query performance', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Record a performance metric
     */
    protected function recordMetric(array $data): void
    {
        try {
            PerformanceMetric::create(array_merge([
                'timestamp' => now(),
            ], $data));
        } catch (\Exception $e) {
            // Fallback to logging if database write fails
            Log::warning('Failed to record performance metric', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Get performance summary for AI services
     */
    public function getAIServiceSummary(int $hours = 24): array
    {
        $since = now()->subHours($hours);

        $metrics = PerformanceMetric::where('type', 'ai_service')
            ->where('timestamp', '>=', $since)
            ->get();

        if ($metrics->isEmpty()) {
            return [
                'total_calls' => 0,
                'success_rate' => 0,
                'average_duration_ms' => 0,
                'median_duration_ms' => 0,
                'p95_duration_ms' => 0,
                'p99_duration_ms' => 0,
                'min_duration_ms' => 0,
                'max_duration_ms' => 0,
                'error_count' => 0,
                'slow_calls' => 0,
                'slow_call_rate' => 0,
                'by_service' => [],
                'by_endpoint' => [],
            ];
        }

        $durations = $metrics->pluck('duration_ms')->sort()->values();
        $successCount = $metrics->where('success', true)->count();
        $errorCount = $metrics->where('success', false)->count();
        $slowCalls = $metrics->where('duration_ms', '>', 2000)->count(); // > 2 seconds
        $totalCalls = $metrics->count();

        return [
            'total_calls' => $totalCalls,
            'success_rate' => $totalCalls > 0 ? round(($successCount / $totalCalls) * 100, 2) : 0,
            'average_duration_ms' => round($durations->avg() ?? 0, 2),
            'median_duration_ms' => round($this->percentile($durations, 50), 2),
            'p95_duration_ms' => round($this->percentile($durations, 95), 2),
            'p99_duration_ms' => round($this->percentile($durations, 99), 2),
            'min_duration_ms' => round($durations->min() ?? 0, 2),
            'max_duration_ms' => round($durations->max() ?? 0, 2),
            'error_count' => $errorCount,
            'slow_calls' => $slowCalls,
            'slow_call_rate' => $totalCalls > 0 ? round(($slowCalls / $totalCalls) * 100, 2) : 0,
            'by_service' => $this->groupByService($metrics),
            'by_endpoint' => $this->groupByEndpoint($metrics),
        ];
    }

    /**
     * Get performance summary for analytics queries
     */
    public function getAnalyticsQuerySummary(int $hours = 24): array
    {
        $since = now()->subHours($hours);

        $metrics = PerformanceMetric::where('type', 'analytics_query')
            ->where('timestamp', '>=', $since)
            ->get();

        if ($metrics->isEmpty()) {
            return [
                'total_queries' => 0,
                'average_duration_ms' => 0,
                'median_duration_ms' => 0,
                'p95_duration_ms' => 0,
                'p99_duration_ms' => 0,
                'min_duration_ms' => 0,
                'max_duration_ms' => 0,
                'slow_queries' => 0,
                'slow_query_rate' => 0,
                'total_rows_processed' => 0,
                'average_rows_per_query' => 0,
                'by_query_type' => [],
            ];
        }

        $durations = $metrics->pluck('duration_ms')->sort()->values();
        $slowQueries = $metrics->where('duration_ms', '>', 1000)->count(); // > 1 second
        $totalRows = $metrics->sum('rows_affected');
        $totalQueries = $metrics->count();

        return [
            'total_queries' => $totalQueries,
            'average_duration_ms' => round($durations->avg() ?? 0, 2),
            'median_duration_ms' => round($this->percentile($durations, 50), 2),
            'p95_duration_ms' => round($this->percentile($durations, 95), 2),
            'p99_duration_ms' => round($this->percentile($durations, 99), 2),
            'min_duration_ms' => round($durations->min() ?? 0, 2),
            'max_duration_ms' => round($durations->max() ?? 0, 2),
            'slow_queries' => $slowQueries,
            'slow_query_rate' => $totalQueries > 0 ? round(($slowQueries / $totalQueries) * 100, 2) : 0,
            'total_rows_processed' => $totalRows,
            'average_rows_per_query' => $totalQueries > 0 ? round($totalRows / $totalQueries, 2) : 0,
            'by_query_type' => $this->groupByQueryType($metrics),
        ];
    }

    /**
     * Get bottleneck analysis
     */
    public function getBottleneckAnalysis(int $hours = 24): array
    {
        $aiSummary = $this->getAIServiceSummary($hours);
        $analyticsSummary = $this->getAnalyticsQuerySummary($hours);

        $bottlenecks = [];

        // AI Service bottlenecks
        if ($aiSummary['average_duration_ms'] > 2000) {
            $bottlenecks[] = [
                'type' => 'ai_service',
                'severity' => 'high',
                'issue' => 'AI service calls are slow on average',
                'average_duration_ms' => $aiSummary['average_duration_ms'],
                'recommendation' => 'Consider optimizing AI service endpoints or implementing caching',
            ];
        }

        if ($aiSummary['error_count'] > 0 && $aiSummary['success_rate'] < 95) {
            $bottlenecks[] = [
                'type' => 'ai_service',
                'severity' => 'high',
                'issue' => 'High error rate in AI service calls',
                'success_rate' => $aiSummary['success_rate'],
                'error_count' => $aiSummary['error_count'],
                'recommendation' => 'Investigate AI service stability and error handling',
            ];
        }

        if ($aiSummary['slow_call_rate'] > 10) {
            $bottlenecks[] = [
                'type' => 'ai_service',
                'severity' => 'medium',
                'issue' => 'High percentage of slow AI service calls',
                'slow_call_rate' => $aiSummary['slow_call_rate'],
                'recommendation' => 'Review AI service performance and consider timeout adjustments',
            ];
        }

        // Analytics Query bottlenecks
        if ($analyticsSummary['average_duration_ms'] > 1000) {
            $bottlenecks[] = [
                'type' => 'analytics_query',
                'severity' => 'high',
                'issue' => 'Analytics queries are slow on average',
                'average_duration_ms' => $analyticsSummary['average_duration_ms'],
                'recommendation' => 'Review database indexes and query optimization',
            ];
        }

        if ($analyticsSummary['slow_query_rate'] > 20) {
            $bottlenecks[] = [
                'type' => 'analytics_query',
                'severity' => 'medium',
                'issue' => 'High percentage of slow analytics queries',
                'slow_query_rate' => $analyticsSummary['slow_query_rate'],
                'recommendation' => 'Optimize slow queries and consider query result caching',
            ];
        }

        // P99 latency issues
        if ($aiSummary['p99_duration_ms'] > 5000) {
            $bottlenecks[] = [
                'type' => 'ai_service',
                'severity' => 'critical',
                'issue' => 'P99 latency for AI services is very high',
                'p99_duration_ms' => $aiSummary['p99_duration_ms'],
                'recommendation' => 'Urgent: Investigate and fix slow AI service calls',
            ];
        }

        if ($analyticsSummary['p99_duration_ms'] > 3000) {
            $bottlenecks[] = [
                'type' => 'analytics_query',
                'severity' => 'critical',
                'issue' => 'P99 latency for analytics queries is very high',
                'p99_duration_ms' => $analyticsSummary['p99_duration_ms'],
                'recommendation' => 'Urgent: Optimize slow analytics queries',
            ];
        }

        return [
            'period_hours' => $hours,
            'bottlenecks' => $bottlenecks,
            'ai_summary' => $aiSummary,
            'analytics_summary' => $analyticsSummary,
            'overall_health' => count($bottlenecks) === 0 ? 'healthy' : (count(array_filter($bottlenecks, fn($b) => $b['severity'] === 'critical')) > 0 ? 'critical' : 'degraded'),
        ];
    }

    /**
     * Get performance trends over time
     */
    public function getPerformanceTrends(int $days = 7, string $type = 'ai_service'): array
    {
        $since = now()->subDays($days);

        $metrics = PerformanceMetric::where('type', $type)
            ->where('timestamp', '>=', $since)
            ->orderBy('timestamp')
            ->get();

        // Group by day
        $grouped = $metrics->groupBy(function ($metric) {
            return $metric->timestamp->format('Y-m-d');
        });

        $trends = [];
        foreach ($grouped as $date => $dayMetrics) {
            $durations = $dayMetrics->pluck('duration_ms');
            $successCount = $dayMetrics->where('success', true)->count();
            $dayCount = $dayMetrics->count();

            $trends[] = [
                'date' => $date,
                'count' => $dayCount,
                'average_duration_ms' => round($durations->avg() ?? 0, 2),
                'p95_duration_ms' => round($this->percentile($durations->sort()->values(), 95), 2),
                'success_rate' => $dayCount > 0 ? round(($successCount / $dayCount) * 100, 2) : 0,
                'error_count' => $dayMetrics->where('success', false)->count(),
            ];
        }

        return $trends;
    }

    /**
     * Calculate percentile
     */
    protected function percentile($sortedValues, float $percentile): float
    {
        if ($sortedValues->isEmpty()) {
            return 0;
        }

        $index = ceil(($percentile / 100) * $sortedValues->count()) - 1;
        return $sortedValues->get($index, 0);
    }

    /**
     * Group metrics by service
     */
    protected function groupByService($metrics): array
    {
        return $metrics->groupBy('category')
            ->map(function ($group) {
                $durations = $group->pluck('duration_ms');
                return [
                    'count' => $group->count(),
                    'average_duration_ms' => round($durations->avg(), 2),
                    'success_rate' => round(($group->where('success', true)->count() / $group->count()) * 100, 2),
                ];
            })
            ->toArray();
    }

    /**
     * Group metrics by endpoint
     */
    protected function groupByEndpoint($metrics): array
    {
        return $metrics->groupBy('operation')
            ->map(function ($group) {
                $durations = $group->pluck('duration_ms');
                return [
                    'count' => $group->count(),
                    'average_duration_ms' => round($durations->avg(), 2),
                    'success_rate' => round(($group->where('success', true)->count() / $group->count()) * 100, 2),
                ];
            })
            ->sortByDesc('count')
            ->take(10) // Top 10 endpoints
            ->toArray();
    }

    /**
     * Group metrics by query type
     */
    protected function groupByQueryType($metrics): array
    {
        return $metrics->groupBy('operation')
            ->map(function ($group) {
                $durations = $group->pluck('duration_ms');
                return [
                    'count' => $group->count(),
                    'average_duration_ms' => round($durations->avg(), 2),
                    'total_rows' => $group->sum('rows_affected'),
                ];
            })
            ->sortByDesc('count')
            ->toArray();
    }

    /**
     * Clean up old performance metrics (older than specified days)
     */
    public function cleanupOldMetrics(int $daysToKeep = 30): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        
        return PerformanceMetric::where('timestamp', '<', $cutoffDate)->delete();
    }
}

