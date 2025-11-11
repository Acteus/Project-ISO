<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Query Logging Middleware
 * 
 * Logs slow database queries and provides query analysis for optimization.
 * Helps identify N+1 query problems and inefficient database operations.
 */
class QueryLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only enable in non-production environments or when explicitly enabled
        // Disabled by default in production for Cloudways compatibility
        $enabled = env('QUERY_LOGGING_ENABLED', false);
        
        // Auto-enable in local/testing if not explicitly disabled
        if ($enabled === false && app()->environment('local', 'testing')) {
            $enabled = true;
        }
        
        if (!$enabled) {
            return $next($request);
        }

        $slowQueryThreshold = env('SLOW_QUERY_THRESHOLD', 100); // milliseconds
        $logAllQueries = env('LOG_ALL_QUERIES', false);

        // Enable query logging
        DB::enableQueryLog();
        
        $startTime = microtime(true);
        $response = $next($request);
        $endTime = microtime(true);
        
        $queries = DB::getQueryLog();
        $totalTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        // Analyze queries
        $this->analyzeQueries($queries, $totalTime, $slowQueryThreshold, $logAllQueries, $request);
        
        return $response;
    }

    /**
     * Analyze database queries for optimization opportunities
     *
     * @param array $queries
     * @param float $totalTime
     * @param int $slowQueryThreshold
     * @param bool $logAllQueries
     * @param Request $request
     * @return void
     */
    protected function analyzeQueries(array $queries, float $totalTime, int $slowQueryThreshold, bool $logAllQueries, Request $request): void
    {
        $queryCount = count($queries);
        $slowQueries = [];
        $duplicateQueries = [];
        $queryPatterns = [];
        $nPlusOneCandidates = [];

        foreach ($queries as $query) {
            $time = $query['time'] ?? 0;
            $sql = $query['query'] ?? '';
            $bindings = $query['bindings'] ?? [];

            // Detect slow queries
            if ($time > $slowQueryThreshold) {
                $slowQueries[] = [
                    'sql' => $sql,
                    'time' => $time,
                    'bindings' => $bindings,
                ];
            }

            // Normalize SQL for pattern detection (remove bindings)
            $normalizedSql = $this->normalizeSql($sql);
            
            // Track query patterns for duplicate detection
            if (!isset($queryPatterns[$normalizedSql])) {
                $queryPatterns[$normalizedSql] = [
                    'count' => 0,
                    'total_time' => 0,
                    'sql' => $sql,
                ];
            }
            $queryPatterns[$normalizedSql]['count']++;
            $queryPatterns[$normalizedSql]['total_time'] += $time;

            // Detect potential N+1 queries (same query executed multiple times)
            if ($queryPatterns[$normalizedSql]['count'] > 5) {
                $nPlusOneCandidates[] = [
                    'sql' => $sql,
                    'count' => $queryPatterns[$normalizedSql]['count'],
                    'total_time' => $queryPatterns[$normalizedSql]['total_time'],
                ];
            }
        }

        // Detect duplicate queries
        foreach ($queryPatterns as $pattern => $data) {
            if ($data['count'] > 1) {
                $duplicateQueries[] = [
                    'sql' => $data['sql'],
                    'count' => $data['count'],
                    'total_time' => $data['total_time'],
                ];
            }
        }

        // Log analysis results
        $this->logAnalysis([
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'total_queries' => $queryCount,
            'total_time_ms' => round($totalTime, 2),
            'avg_query_time_ms' => $queryCount > 0 ? round($totalTime / $queryCount, 2) : 0,
            'slow_queries' => $slowQueries,
            'duplicate_queries' => $duplicateQueries,
            'n_plus_one_candidates' => $nPlusOneCandidates,
        ], $logAllQueries);
    }

    /**
     * Normalize SQL query for pattern matching
     *
     * @param string $sql
     * @return string
     */
    protected function normalizeSql(string $sql): string
    {
        // Remove extra whitespace
        $sql = preg_replace('/\s+/', ' ', $sql);
        
        // Replace bindings with placeholders
        $sql = preg_replace('/\?/', '?', $sql);
        
        // Remove table prefixes if any
        $sql = preg_replace('/`?\w+`?\./', '', $sql);
        
        return trim($sql);
    }

    /**
     * Log query analysis results
     *
     * @param array $analysis
     * @param bool $logAllQueries
     * @return void
     */
    protected function logAnalysis(array $analysis, bool $logAllQueries): void
    {
        $hasIssues = !empty($analysis['slow_queries']) || 
                     !empty($analysis['duplicate_queries']) || 
                     !empty($analysis['n_plus_one_candidates']);

        // Always log if there are performance issues
        if ($hasIssues) {
            Log::warning('Database Query Performance Issues Detected', $analysis);
        } elseif ($logAllQueries && $analysis['total_queries'] > 0) {
            // Log all queries if enabled
            Log::info('Database Query Analysis', [
                'url' => $analysis['url'],
                'total_queries' => $analysis['total_queries'],
                'total_time_ms' => $analysis['total_time_ms'],
            ]);
        }

        // Log critical N+1 problems separately
        if (!empty($analysis['n_plus_one_candidates'])) {
            foreach ($analysis['n_plus_one_candidates'] as $candidate) {
                Log::error('Potential N+1 Query Problem Detected', [
                    'sql' => $candidate['sql'],
                    'execution_count' => $candidate['count'],
                    'total_time_ms' => round($candidate['total_time'], 2),
                    'recommendation' => 'Consider using eager loading (with() or load()) to reduce query count',
                ]);
            }
        }
    }
}

