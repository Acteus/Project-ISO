<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\VisualizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin Authentication Routes
// SECURITY FIX: Add rate limiting to admin authentication endpoints
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/admin/me', [AdminAuthController::class, 'me'])->middleware('auth:sanctum');

// Survey Routes (public routes that don't require authentication)
// SECURITY NOTE: This route uses web middleware for session support but is excluded from CSRF
// because it's a public endpoint for external form submissions. It's protected by:
// - Rate limiting: 10 submissions per minute per IP to prevent abuse
// - Input validation and sanitization
// - Session-based tracking for authenticated users (optional)
Route::post('/survey/submit', [SurveyController::class, 'submitResponse'])
    ->middleware(['web', 'throttle:10,1']);

// Protected Admin Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/survey/responses', [SurveyController::class, 'getAllResponses']);
    Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse']);
    Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse']);
    Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics']);

    // ==========================================
    // NEW SIMPLIFIED ANALYTICS API (v2)
    // ==========================================
    // These 3 endpoints replace all the complex visualization routes below
    // Cached for 5 minutes (300 seconds) to improve performance
    Route::get('/analytics/summary', [AnalyticsController::class, 'getSummary'])
        ->middleware('cache.api:300');
    Route::get('/analytics/time-series', [AnalyticsController::class, 'getTimeSeries'])
        ->middleware('cache.api:300');
    Route::get('/analytics/compliance', [AnalyticsController::class, 'getCompliance'])
        ->middleware('cache.api:300');

    // AI Routes
    // SECURITY FIX: Add rate limiting to AI service endpoints to prevent abuse
    Route::post('/ai/compliance-predict', [AIController::class, 'predictCompliance'])->middleware('throttle:30,1'); // 30 requests per minute
    Route::get('/ai/cluster-responses', [AIController::class, 'clusterResponses'])->middleware('throttle:30,1');
    Route::get('/ai/sentiment-analysis', [AIController::class, 'analyzeSentiment'])->middleware('throttle:30,1');
    Route::get('/ai/keyword-extraction', [AIController::class, 'extractKeywords'])->middleware('throttle:30,1');

    // Performance Monitoring Routes
    Route::prefix('performance')->group(function () {
        Route::get('/ai-service', [PerformanceController::class, 'getAIServiceSummary']);
        Route::get('/analytics-queries', [PerformanceController::class, 'getAnalyticsQuerySummary']);
        Route::get('/bottlenecks', [PerformanceController::class, 'getBottleneckAnalysis']);
        Route::get('/trends', [PerformanceController::class, 'getPerformanceTrends']);
        Route::get('/dashboard', [PerformanceController::class, 'getDashboard']);
    });
    Route::get('/ai/compliance-risk-meter', [AIController::class, 'getComplianceRiskMeter'])
        ->middleware(['cache.api:300', 'throttle:30,1']);

    // ==========================================
    // VISUALIZATION ROUTES REMOVED
    // ==========================================
    // These routes have been moved to routes/web.php with session-based authentication
    // for use with the admin dashboard. They use 'auth' and 'admin' middleware instead
    // of 'auth:sanctum' to work with web sessions.
    // 
    // For API access with tokens, use the new /api/analytics/* endpoints above.
    // For web dashboard access, the routes are available in routes/web.php under
    // /api prefix with session authentication (auth + admin middleware).

    // Export Routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/export/csv', [ExportController::class, 'exportCsv']);
    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
    Route::get('/export/analytics-report', [ExportController::class, 'exportAnalyticsReport']);

    // AI Service Status and Analysis routes
    Route::get('/ai/service-status', [AIController::class, 'getServiceStatus'])
        ->middleware(['cache.api:60', 'throttle:60,1']); // Cache for 1 minute, 60 requests per minute
    Route::get('/ai/metrics', [AIController::class, 'getAIMetrics'])
        ->middleware(['cache.api:300', 'throttle:30,1']); // Cache for 5 minutes, 30 requests per minute
    Route::post('/ai/analyze/{type}', [AIController::class, 'runAnalysis'])->middleware('throttle:20,1'); // 20 requests per minute

    // Compliance and Audit Routes
    Route::get('/compliance/status', [ComplianceController::class, 'getComplianceStatus'])
        ->middleware('cache.api:300');
    Route::get('/compliance/metrics', [ComplianceController::class, 'getComplianceMetrics'])
        ->middleware('cache.api:300');
    Route::get('/compliance/audit-logs', [ComplianceController::class, 'getAuditLogs']);
    Route::get('/compliance/audit-trail/{resourceType}/{resourceId}', [ComplianceController::class, 'getResourceAuditTrail']);
});
