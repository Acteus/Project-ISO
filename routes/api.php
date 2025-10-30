<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\VisualizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin Authentication Routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/admin/me', [AdminAuthController::class, 'me'])->middleware('auth:sanctum');

// Survey Routes (public routes that don't require authentication or CSRF)
// Note: Using web middleware to maintain session for authenticated users
Route::post('/survey/submit', [SurveyController::class, 'submitResponse'])
    ->middleware('web')
    ->withoutMiddleware(['throttle']);

// Protected Admin Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/survey/responses', [SurveyController::class, 'getAllResponses']);
    Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse']);
    Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse']);
    Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics']);

    // AI Routes
    Route::post('/ai/compliance-predict', [AIController::class, 'predictCompliance']);
    Route::get('/ai/cluster-responses', [AIController::class, 'clusterResponses']);
    Route::get('/ai/sentiment-analysis', [AIController::class, 'analyzeSentiment']);
    Route::get('/ai/keyword-extraction', [AIController::class, 'extractKeywords']);
    Route::get('/ai/compliance-risk-meter', [AIController::class, 'getComplianceRiskMeter']);

    // Visualization Routes
    Route::get('/visualizations/bar-chart', [VisualizationController::class, 'getBarChartData']);
    Route::get('/visualizations/pie-chart', [VisualizationController::class, 'getPieChartData']);
    Route::get('/visualizations/radar-chart', [VisualizationController::class, 'getRadarChartData']);
    Route::get('/visualizations/word-cloud', [VisualizationController::class, 'getWordCloudData']);
    Route::get('/visualizations/program-comparison', [VisualizationController::class, 'getProgramComparisonData']);
    Route::get('/visualizations/year-trend', [VisualizationController::class, 'getYearLevelTrendData']);
    Route::get('/visualizations/dashboard', [VisualizationController::class, 'getDashboardData']);

    // Advanced Analytics Routes
    Route::get('/visualizations/time-series', [VisualizationController::class, 'getTimeSeriesData']);
    Route::get('/visualizations/heat-map', [VisualizationController::class, 'getHeatMapData']);
    Route::get('/visualizations/compliance-risk', [VisualizationController::class, 'getComplianceRiskData']);
    Route::get('/visualizations/comparative-analysis', [VisualizationController::class, 'getComparativeAnalysis']);
    Route::get('/visualizations/response-rate', [VisualizationController::class, 'getResponseRateAnalytics']);

    // Weekly Progress Tracking Routes
    Route::get('/visualizations/weekly-progress', [VisualizationController::class, 'getWeeklyProgressData']);
    Route::get('/visualizations/goal-progress', [VisualizationController::class, 'getGoalProgressData']);
    Route::get('/visualizations/weekly-comparison', [VisualizationController::class, 'getWeeklyComparisonData']);
    Route::get('/visualizations/monthly-report', [VisualizationController::class, 'getMonthlyReportData']);
    Route::get('/visualizations/progress-alerts', [VisualizationController::class, 'getProgressAlerts']);

    // Export Routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/export/csv', [ExportController::class, 'exportCsv']);
    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
    Route::get('/export/analytics-report', [ExportController::class, 'exportAnalyticsReport']);

    // AI Service Status and Analysis routes
    Route::get('/ai/service-status', [AIController::class, 'getServiceStatus']);
    Route::get('/ai/metrics', [AIController::class, 'getAIMetrics']);
    Route::post('/ai/analyze/{type}', [AIController::class, 'runAnalysis']);
});
