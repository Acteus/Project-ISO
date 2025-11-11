<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\InputSanitizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Analytics Controller - Simplified API
 *
 * Provides 3 clean endpoints for dashboard data:
 * 1. Summary - All dashboard metrics in one call
 * 2. Time Series - Trend data over time
 * 3. Compliance - Risk assessment details
 */
class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Get comprehensive analytics summary
     *
     * Single endpoint that returns all data needed for the dashboard:
     * - ISO 21001 indices
     * - Overall satisfaction
     * - Distribution by grade/gender/semester
     * - Compliance score and risk assessment
     * - Basic trends
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(Request $request)
    {
        try {
            // Validate and sanitize query parameters
            $validator = Validator::make($request->query(), [
                'track' => 'nullable|in:CSS',
                'grade_level' => 'nullable|integer|in:11,12',
                'semester' => 'nullable|in:1st,2nd',
                'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$|^\d{4}$/',
                'gender' => 'nullable|in:Male,Female,Non-binary,Prefer not to say',
                'date_from' => 'nullable|date|date_format:Y-m-d',
                'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sanitizationService = app(InputSanitizationService::class);
            $filters = [
                'track' => $sanitizationService->sanitizeQueryParameter($request->query('track'), 'track'),
                'grade_level' => $sanitizationService->sanitizeQueryParameter($request->query('grade_level'), 'grade_level'),
                'semester' => $sanitizationService->sanitizeQueryParameter($request->query('semester'), 'semester'),
                'academic_year' => $sanitizationService->sanitizeQueryParameter($request->query('academic_year'), 'academic_year'),
                'gender' => $sanitizationService->sanitizeQueryParameter($request->query('gender'), 'gender'),
                'date_from' => $sanitizationService->sanitizeQueryParameter($request->query('date_from'), 'date'),
                'date_to' => $sanitizationService->sanitizeQueryParameter($request->query('date_to'), 'date'),
            ];

            // Generate cache key from filters
            $cacheKey = 'analytics:summary:' . md5(serialize($filters));
            
            // Use CacheService for consistent caching
            $data = \App\Services\CacheService::remember($cacheKey, function () use ($filters) {
                return $this->analyticsService->getAnalyticsSummary($filters);
            }, 'analytics');

            return response()->json([
                'success' => true,
                'message' => 'Analytics summary retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Analytics summary error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analytics data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get time series trend data
     *
     * Returns data for line/bar charts showing trends over time
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeSeries(Request $request)
    {
        try {
            // Validate and sanitize query parameters
            $validator = Validator::make($request->query(), [
                'metric' => 'nullable|string|in:overall_satisfaction,learner_needs_index,satisfaction_score,success_index,safety_index,wellbeing_index',
                'group_by' => 'nullable|string|in:day,week,month',
                'track' => 'nullable|in:CSS',
                'grade_level' => 'nullable|integer|in:11,12',
                'semester' => 'nullable|in:1st,2nd',
                'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$|^\d{4}$/',
                'gender' => 'nullable|in:Male,Female,Non-binary,Prefer not to say',
                'date_from' => 'nullable|date|date_format:Y-m-d',
                'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sanitizationService = app(InputSanitizationService::class);
            $metric = $sanitizationService->sanitizeQueryParameter($request->query('metric', 'overall_satisfaction'), 'string') ?: 'overall_satisfaction';
            $groupBy = $sanitizationService->sanitizeQueryParameter($request->query('group_by', 'week'), 'string');
            $groupBy = in_array($groupBy, ['day', 'week', 'month']) ? $groupBy : 'week';

            $filters = [
                'track' => $sanitizationService->sanitizeQueryParameter($request->query('track'), 'track'),
                'grade_level' => $sanitizationService->sanitizeQueryParameter($request->query('grade_level'), 'grade_level'),
                'semester' => $sanitizationService->sanitizeQueryParameter($request->query('semester'), 'semester'),
                'academic_year' => $sanitizationService->sanitizeQueryParameter($request->query('academic_year'), 'academic_year'),
                'gender' => $sanitizationService->sanitizeQueryParameter($request->query('gender'), 'gender'),
                'date_from' => $sanitizationService->sanitizeQueryParameter($request->query('date_from'), 'date'),
                'date_to' => $sanitizationService->sanitizeQueryParameter($request->query('date_to'), 'date'),
            ];

            // Generate cache key from filters and parameters
            $cacheKey = 'analytics:timeseries:' . md5(serialize([$metric, $groupBy, $filters]));
            
            // Use CacheService for consistent caching
            $data = \App\Services\CacheService::remember($cacheKey, function () use ($metric, $groupBy, $filters) {
                return $this->analyticsService->getTimeSeriesData($metric, $groupBy, $filters);
            }, 'analytics');

            return response()->json([
                'success' => true,
                'message' => 'Time series data retrieved successfully',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Time series error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve time series data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get detailed compliance assessment
     *
     * Returns risk level, score, and recommendations
     * This is already included in summary but provided separately
     * for dedicated compliance dashboard views
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompliance(Request $request)
    {
        try {
            // Validate and sanitize query parameters
            $validator = Validator::make($request->query(), [
                'track' => 'nullable|in:CSS',
                'grade_level' => 'nullable|integer|in:11,12',
                'semester' => 'nullable|in:1st,2nd',
                'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$|^\d{4}$/',
                'gender' => 'nullable|in:Male,Female,Non-binary,Prefer not to say',
                'date_from' => 'nullable|date|date_format:Y-m-d',
                'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $sanitizationService = app(InputSanitizationService::class);
            $filters = [
                'track' => $sanitizationService->sanitizeQueryParameter($request->query('track'), 'track'),
                'grade_level' => $sanitizationService->sanitizeQueryParameter($request->query('grade_level'), 'grade_level'),
                'semester' => $sanitizationService->sanitizeQueryParameter($request->query('semester'), 'semester'),
                'academic_year' => $sanitizationService->sanitizeQueryParameter($request->query('academic_year'), 'academic_year'),
                'gender' => $sanitizationService->sanitizeQueryParameter($request->query('gender'), 'gender'),
                'date_from' => $sanitizationService->sanitizeQueryParameter($request->query('date_from'), 'date'),
                'date_to' => $sanitizationService->sanitizeQueryParameter($request->query('date_to'), 'date'),
            ];

            // Generate cache key from filters
            $cacheKey = 'analytics:compliance:' . md5(serialize($filters));
            
            // Use CacheService for consistent caching
            $summary = \App\Services\CacheService::remember($cacheKey, function () use ($filters) {
                return $this->analyticsService->getAnalyticsSummary($filters);
            }, 'analytics');

            if (!$summary['has_data']) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available for compliance assessment',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Compliance assessment retrieved successfully',
                'data' => [
                    'compliance' => $summary['compliance'],
                    'iso_indices' => $summary['iso_indices'],
                    'total_responses' => $summary['total_responses'],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Compliance assessment error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve compliance data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
