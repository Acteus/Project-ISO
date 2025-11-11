<?php

namespace App\Http\Controllers;

use App\Services\PerformanceMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Performance Monitoring Controller
 * 
 * Provides endpoints to view performance metrics and identify bottlenecks
 */
class PerformanceController extends Controller
{
    protected $monitoringService;

    public function __construct(PerformanceMonitoringService $monitoringService)
    {
        $this->monitoringService = $monitoringService;
    }

    /**
     * Get AI service performance summary
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAIServiceSummary(Request $request)
    {
        try {
            $hours = (int) $request->query('hours', 24);
            
            $summary = $this->monitoringService->getAIServiceSummary($hours);

            return response()->json([
                'success' => true,
                'data' => $summary,
                'period_hours' => $hours,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get AI service summary', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve AI service performance data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get analytics query performance summary
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalyticsQuerySummary(Request $request)
    {
        try {
            $hours = (int) $request->query('hours', 24);
            
            $summary = $this->monitoringService->getAnalyticsQuerySummary($hours);

            return response()->json([
                'success' => true,
                'data' => $summary,
                'period_hours' => $hours,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get analytics query summary', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve analytics query performance data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get bottleneck analysis
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBottleneckAnalysis(Request $request)
    {
        try {
            $hours = (int) $request->query('hours', 24);
            
            $analysis = $this->monitoringService->getBottleneckAnalysis($hours);

            return response()->json([
                'success' => true,
                'data' => $analysis,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get bottleneck analysis', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve bottleneck analysis',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get performance trends over time
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPerformanceTrends(Request $request)
    {
        try {
            $days = (int) $request->query('days', 7);
            $type = $request->query('type', 'ai_service'); // 'ai_service' or 'analytics_query'
            
            $trends = $this->monitoringService->getPerformanceTrends($days, $type);

            return response()->json([
                'success' => true,
                'data' => $trends,
                'period_days' => $days,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get performance trends', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance trends',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get comprehensive performance dashboard data
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboard(Request $request)
    {
        try {
            $hours = (int) $request->query('hours', 24);
            
            $aiSummary = $this->monitoringService->getAIServiceSummary($hours);
            $analyticsSummary = $this->monitoringService->getAnalyticsQuerySummary($hours);
            $bottleneckAnalysis = $this->monitoringService->getBottleneckAnalysis($hours);
            $aiTrends = $this->monitoringService->getPerformanceTrends(7, 'ai_service');
            $analyticsTrends = $this->monitoringService->getPerformanceTrends(7, 'analytics_query');

            return response()->json([
                'success' => true,
                'data' => [
                    'ai_service' => $aiSummary,
                    'analytics_queries' => $analyticsSummary,
                    'bottlenecks' => $bottleneckAnalysis,
                    'trends' => [
                        'ai_service' => $aiTrends,
                        'analytics_queries' => $analyticsTrends,
                    ],
                ],
                'period_hours' => $hours,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get performance dashboard', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve performance dashboard data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show performance monitoring dashboard (web view)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showDashboard(Request $request)
    {
        try {
            $hours = (int) $request->query('hours', 24);
            
            $aiSummary = $this->monitoringService->getAIServiceSummary($hours);
            $analyticsSummary = $this->monitoringService->getAnalyticsQuerySummary($hours);
            $bottleneckAnalysis = $this->monitoringService->getBottleneckAnalysis($hours);
            $aiTrends = $this->monitoringService->getPerformanceTrends(7, 'ai_service');
            $analyticsTrends = $this->monitoringService->getPerformanceTrends(7, 'analytics_query');

            return view('admin.performance', [
                'aiSummary' => $aiSummary,
                'analyticsSummary' => $analyticsSummary,
                'bottleneckAnalysis' => $bottleneckAnalysis,
                'aiTrends' => $aiTrends,
                'analyticsTrends' => $analyticsTrends,
                'hours' => $hours,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to show performance dashboard', [
                'error' => $e->getMessage(),
            ]);

            return view('admin.performance', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
