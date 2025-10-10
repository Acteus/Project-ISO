<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\VisualizationController;

// Serve the custom homepage
Route::get('/', function () {
    return response()->file(public_path('Index.html'));
});

// Serve specific static HTML pages
Route::get('/survey.html', function () {
    return response()->file(public_path('survey.html'));
});

Route::get('/dashboard.html', function () {
    return response()->file(public_path('dashboard.html'));
});

Route::get('/thank-you.html', function () {
    return response()->file(public_path('thank-you.html'));
});

Route::get('/Login.html', function () {
    return response()->file(public_path('Login.html'));
});

// Catch-all route for any HTML files in public directory
Route::get('/{filename}.html', function ($filename) {
    $filePath = public_path($filename . '.html');

    if (file_exists($filePath)) {
        return response()->file($filePath);
    }

    abort(404);
})->where('filename', '.*');

// Serve static assets (CSS, JS, images)
Route::get('/{filename}.css', function ($filename) {
    $filePath = public_path($filename . '.css');

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'text/css'
        ]);
    }

    abort(404);
})->where('filename', '.*');

Route::get('/{filename}.js', function ($filename) {
    $filePath = public_path($filename . '.js');

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'application/javascript'
        ]);
    }

    abort(404);
})->where('filename', '.*');

// Serve images
Route::get('/{filename}.jpg', function ($filename) {
    $filePath = public_path($filename . '.jpg');

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'image/jpeg'
        ]);
    }

    abort(404);
})->where('filename', '.*');

Route::get('/{filename}.png', function ($filename) {
    $filePath = public_path($filename . '.png');

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'image/png'
        ]);
    }

    abort(404);
})->where('filename', '.*');

// API Routes for Survey functionality
Route::prefix('api')->group(function () {
    // Survey routes
    Route::post('/survey/submit', [SurveyController::class, 'submitResponse']);
    Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics']);
    Route::get('/survey/responses', [SurveyController::class, 'getAllResponses']);
    Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse']);
    Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse']);

    // Admin authentication routes
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::get('/admin/me', [AdminAuthController::class, 'me']);

    // AI-powered analytics routes
    Route::post('/ai/predict-compliance', [AIController::class, 'predictCompliance']);
    Route::post('/ai/cluster-responses', [AIController::class, 'clusterResponses']);
    Route::post('/ai/analyze-sentiment', [AIController::class, 'analyzeSentiment']);
    Route::post('/ai/extract-keywords', [AIController::class, 'extractKeywords']);
    Route::get('/ai/compliance-risk-meter', [AIController::class, 'getComplianceRiskMeter']);

    // Export routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel']);
    Route::get('/export/csv', [ExportController::class, 'exportCsv']);
    Route::get('/export/pdf', [ExportController::class, 'exportPdf']);
    Route::get('/export/analytics-report', [ExportController::class, 'exportAnalyticsReport']);

    // Visualization routes
    Route::get('/visualization/bar-chart', [VisualizationController::class, 'getBarChartData']);
    Route::get('/visualization/pie-chart', [VisualizationController::class, 'getPieChartData']);
    Route::get('/visualization/radar-chart', [VisualizationController::class, 'getRadarChartData']);
    Route::get('/visualization/word-cloud', [VisualizationController::class, 'getWordCloudData']);
    Route::get('/visualization/track-comparison', [VisualizationController::class, 'getTrackComparisonData']);
    Route::get('/visualization/grade-trend', [VisualizationController::class, 'getGradeLevelTrendData']);
    Route::get('/visualization/dashboard', [VisualizationController::class, 'getDashboardData']);
});
