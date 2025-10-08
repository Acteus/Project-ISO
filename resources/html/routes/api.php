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

// Survey Routes
Route::post('/survey/submit', [SurveyController::class, 'submitResponse']);

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

    // Export Routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/export/csv', [ExportController::class, 'exportCsv']);
    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
    Route::get('/export/analytics-report', [ExportController::class, 'exportAnalyticsReport']);
});
