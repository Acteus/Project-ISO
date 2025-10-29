<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisualizationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\QrCodeController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Survey home/landing page
Route::get('/home', function () {
    return view('survey.landing');
})->name('home');

// Student authentication routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register', [StudentController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentController::class, 'register'])->name('register.post');
    Route::get('/login', [StudentController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentController::class, 'login'])->name('login.post');
    Route::match(['get', 'post'], '/logout', [StudentController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
});

// Password Reset Routes
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
    Route::get('/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [ForgotPasswordController::class, 'reset'])->name('update');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/responses', [StudentController::class, 'allResponses'])->name('responses');
    Route::get('/responses/{id}', [StudentController::class, 'viewResponse'])->name('response.view');
    Route::get('/audit-logs', [StudentController::class, 'auditLogs'])->name('audit.logs');

    // Report Management Routes
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::post('/reports/send-weekly', [App\Http\Controllers\Admin\ReportController::class, 'sendWeeklyReport'])->name('reports.send-weekly');
    Route::post('/reports/send-monthly', [App\Http\Controllers\Admin\ReportController::class, 'sendMonthlyReport'])->name('reports.send-monthly');
    Route::post('/reports/preview-weekly', [App\Http\Controllers\Admin\ReportController::class, 'previewWeeklyReport'])->name('reports.preview-weekly');
    Route::post('/reports/preview-monthly', [App\Http\Controllers\Admin\ReportController::class, 'previewMonthlyReport'])->name('reports.preview-monthly');

    // Goal Management Routes
    Route::resource('goals', App\Http\Controllers\Admin\GoalController::class);
    Route::post('goals/{goal}/progress', [App\Http\Controllers\Admin\GoalController::class, 'updateProgress'])->name('goals.update-progress');

    // AI Insights Routes
    Route::get('/ai-insights', [StudentController::class, 'aiInsights'])->name('ai.insights');

    // QR Code Management Routes
    Route::resource('qr-codes', QrCodeController::class);
    Route::post('/qr-codes/batch-generate', [QrCodeController::class, 'batchGenerate'])->name('qr-codes.batch-generate');
    Route::get('/qr-codes/{id}/download', [QrCodeController::class, 'download'])->name('qr-codes.download');
    Route::get('/qr-codes/statistics', [QrCodeController::class, 'statistics'])->name('qr-codes.statistics');
    Route::get('/qr-codes/export', [QrCodeController::class, 'export'])->name('qr-codes.export');
});

// Survey routes
Route::get('/survey', function () {
    return view('survey.form');
})->name('survey.form');

Route::get('/survey/landing', function () {
    return view('survey.landing');
})->name('survey.landing');

Route::get('/survey/about', function () {
    return view('survey.about');
})->name('survey.about');

Route::get('/survey/privacy', function () {
    return view('survey.privacy');
})->name('survey.privacy');

Route::get('/survey/contact', function () {
    return view('survey.contact');
})->name('survey.contact');

Route::get('/thank-you', function () {
    return view('survey.thankyou');
})->name('survey.thankyou');

// QR Code public access route
Route::get('/qr/{id}', [QrCodeController::class, 'showPublic'])->name('qr.show');

// Serve static assets from public directory
Route::get('/css/{filename}', function ($filename) {
    $filePath = public_path('css/' . $filename);

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'text/css'
        ]);
    }

    abort(404);
})->where('filename', '.*');

Route::get('/js/{filename}', function ($filename) {
    $filePath = public_path('js/' . $filename);

    if (file_exists($filePath)) {
        return response()->file($filePath, [
            'Content-Type' => 'application/javascript'
        ]);
    }

    abort(404);
})->where('filename', '.*');

// Serve images from public directory
Route::get('/images/{filename}', function ($filename) {
    $filePath = public_path('images/' . $filename);

    if (file_exists($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
        ];

        return response()->file($filePath, [
            'Content-Type' => $mimeTypes[$extension] ?? 'application/octet-stream'
        ]);
    }

    abort(404);
})->where('filename', '.*');

// Redirect old static HTML URLs to new Laravel routes
Route::get('/Login.html', function () {
    return redirect()->route('student.login');
});

Route::get('/Index.html', function () {
    return redirect()->route('home');
});

Route::get('/survey.html', function () {
    return redirect()->route('survey.form');
});

Route::get('/dashboard.html', function () {
    return redirect()->route('student.dashboard');
});

Route::get('/thank-you.html', function () {
    return redirect()->route('survey.thankyou');
});

// Debug logging for troubleshooting
Route::get('/debug-login', function () {
    Log::info('Login debug route accessed', [
        'url' => request()->url(),
        'full_url' => request()->fullUrl(),
        'method' => request()->method(),
        'user_agent' => request()->userAgent()
    ]);
    return response()->json(['message' => 'Debug route working']);
});

// API Routes for Survey functionality
Route::prefix('api')->group(function () {
    // Survey routes
    Route::post('/survey/submit', [SurveyController::class, 'submitResponse'])->name('survey.submit');
    Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics'])->name('api.survey.analytics');
    Route::get('/survey/responses', [SurveyController::class, 'getAllResponses'])->name('api.survey.responses');
    Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse'])->name('api.survey.response');
    Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse'])->name('api.survey.delete');

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

    // AI Service Status and Analysis routes (session-based auth for admin dashboard)
    Route::get('/ai/service-status', [AIController::class, 'getServiceStatus']);
    Route::get('/ai/metrics', [AIController::class, 'getAIMetrics']);
    Route::post('/ai/analyze/{type}', [AIController::class, 'runAnalysis']);

    // Export routes
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('api.export.excel');
    Route::get('/export/csv', [ExportController::class, 'exportCsv'])->name('api.export.csv');
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('api.export.pdf');
    Route::get('/export/analytics-report', [ExportController::class, 'exportAnalyticsReport'])->name('api.export.analytics-report');

    // Visualization routes
    Route::get('/visualization/bar-chart', [VisualizationController::class, 'getBarChartData']);
    Route::get('/visualization/pie-chart', [VisualizationController::class, 'getPieChartData']);
    Route::get('/visualization/radar-chart', [VisualizationController::class, 'getRadarChartData']);
    Route::get('/visualization/word-cloud', [VisualizationController::class, 'getWordCloudData']);
    Route::get('/visualization/track-comparison', [VisualizationController::class, 'getTrackComparisonData']);
    Route::get('/visualization/grade-trend', [VisualizationController::class, 'getGradeLevelTrendData']);
    Route::get('/visualization/dashboard', [VisualizationController::class, 'getDashboardData']);

    // Advanced Analytics Visualization routes
    Route::get('/visualizations/time-series', [VisualizationController::class, 'getTimeSeriesData']);
    Route::get('/visualizations/heat-map', [VisualizationController::class, 'getHeatMapData']);
    Route::get('/visualizations/compliance-risk', [VisualizationController::class, 'getComplianceRiskData']);
    Route::get('/visualizations/comparative-analysis', [VisualizationController::class, 'getComparativeAnalysis']);
    Route::get('/visualizations/response-rate', [VisualizationController::class, 'getResponseRateAnalytics']);

    // AI Analytics routes
    Route::get('/ai/sentiment-analysis', [AIController::class, 'analyzeSentiment']);
});
