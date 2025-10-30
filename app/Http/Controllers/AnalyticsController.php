<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            $filters = [
                'track' => $request->query('track'),
                'grade_level' => $request->query('grade_level'),
                'semester' => $request->query('semester'),
                'academic_year' => $request->query('academic_year'),
                'gender' => $request->query('gender'),
                'date_from' => $request->query('date_from'),
                'date_to' => $request->query('date_to'),
            ];

            $data = $this->analyticsService->getAnalyticsSummary($filters);

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
            $metric = $request->query('metric', 'overall_satisfaction');
            $groupBy = $request->query('group_by', 'week'); // day, week, month

            $filters = [
                'track' => $request->query('track'),
                'grade_level' => $request->query('grade_level'),
                'semester' => $request->query('semester'),
                'academic_year' => $request->query('academic_year'),
                'gender' => $request->query('gender'),
                'date_from' => $request->query('date_from'),
                'date_to' => $request->query('date_to'),
            ];

            $data = $this->analyticsService->getTimeSeriesData($metric, $groupBy, $filters);

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
            $filters = [
                'track' => $request->query('track'),
                'grade_level' => $request->query('grade_level'),
                'semester' => $request->query('semester'),
                'academic_year' => $request->query('academic_year'),
                'gender' => $request->query('gender'),
                'date_from' => $request->query('date_from'),
                'date_to' => $request->query('date_to'),
            ];

            $summary = $this->analyticsService->getAnalyticsSummary($filters);

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
