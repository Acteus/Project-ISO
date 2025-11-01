<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisualizationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\QrCodeController;

// Health check endpoint for Railway monitoring (no middleware)
Route::middleware([])->get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
})->name('health');

// Detailed health check endpoint for monitoring (doesn't block deployment)
Route::get('/health/detailed', function () {
    try {
        // Check database connection
        $databaseStatus = 'ok';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $databaseStatus = 'error: ' . $e->getMessage();
        }

        // Check cache connection
        $cacheStatus = 'ok';
        try {
            Cache::store()->getStore();
        } catch (\Exception $e) {
            $cacheStatus = 'error: ' . $e->getMessage();
        }

        // Check AI service connection (optional)
        $aiServiceStatus = 'unknown';
        try {
            $aiClient = app(\App\Services\FlaskAIClient::class);
            $status = $aiClient->getServiceStatus();
            $aiServiceStatus = $status['available'] ? 'ok' : 'error';
        } catch (\Exception $e) {
            $aiServiceStatus = 'error: ' . $e->getMessage();
        }

        $status = ($databaseStatus === 'ok' && $cacheStatus === 'ok') ? 'healthy' : 'degraded';

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toISOString(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env'),
            'services' => [
                'database' => $databaseStatus,
                'cache' => $cacheStatus,
                'ai_service' => $aiServiceStatus,
            ],
            'metrics' => [
                'memory_usage' => memory_get_peak_usage(true),
                'uptime' => now()->diffInSeconds(now()->startOfDay()),
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
})->name('health.detailed');

// Public routes
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
    Route::get('/clear-sessions', [StudentController::class, 'clearAllSessions'])->name('clear-sessions');
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
    Route::post('/reports/test-email', [App\Http\Controllers\Admin\ReportController::class, 'testEmail'])->name('reports.test-email');

    // Goal Management Routes
    Route::resource('goals', App\Http\Controllers\Admin\GoalController::class);
    Route::post('goals/{goal}/progress', [App\Http\Controllers\Admin\GoalController::class, 'updateProgress'])->name('goals.update-progress');

    // AI Insights Routes
    Route::get('/ai-insights', [StudentController::class, 'aiInsights'])->name('ai.insights');

    // QR Code Management Routes
    // Note: Specific routes must come BEFORE resource routes to avoid conflicts
    Route::get('qr-codes/export', [QrCodeController::class, 'export'])->name('qr-codes.export');
    Route::get('qr-codes/statistics', [QrCodeController::class, 'statistics'])->name('qr-codes.statistics');
    Route::post('qr-codes/batch-generate', [QrCodeController::class, 'batchGenerate'])->name('qr-codes.batch-generate');
    Route::get('qr-codes/{id}/download', [QrCodeController::class, 'download'])->name('qr-codes.download');
    Route::resource('qr-codes', QrCodeController::class);
});

// Survey routes
Route::get('/survey', [SurveyController::class, 'showForm'])->name('survey.form');
Route::get('/survey/landing', [SurveyController::class, 'landing'])->name('survey.landing');

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

// Auth debug route
Route::get('/debug-auth', function () {
    $user = Auth::user();
    $admin = session('admin');
    $sessionId = session()->getId();

    // Get session data from database
    $sessionData = DB::table('sessions')->where('id', $sessionId)->first();

    return response()->json([
        'authenticated' => Auth::check(),
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'student_id' => $user->student_id,
        ] : null,
        'admin' => $admin ? [
            'id' => $admin->id,
            'name' => $admin->name,
        ] : null,
        'session_id' => substr($sessionId, 0, 10) . '...',
        'session_has_user_id' => $sessionData ? $sessionData->user_id : 'NO SESSION FOUND',
        'session_last_activity' => $sessionData ? date('Y-m-d H:i:s', $sessionData->last_activity) : null,
        'all_session_data' => session()->all(),
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'app_url' => config('app.url'),
        'request_url' => request()->url(),
        'request_host' => request()->getHost(),
        'cookies' => request()->cookies->keys(),
    ]);
});

// Manual login test route
Route::get('/test-login/{studentId}', function ($studentId) {
    $user = \App\Models\User::where('student_id', $studentId)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }

    Auth::login($user);
    request()->session()->regenerate();

    return response()->json([
        'message' => 'Manually logged in',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'student_id' => $user->student_id,
        ],
        'authenticated' => Auth::check(),
        'session_id' => session()->getId(),
        'redirect_to' => route('survey.landing'),
    ]);
})->where('studentId', '.*');

// API Routes for Survey functionality
Route::prefix('api')->group(function () {
    // Survey routes
    Route::post('/survey/submit', [SurveyController::class, 'submitResponse'])->name('survey.submit');
    Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics'])->name('api.survey.analytics');
    Route::get('/survey/responses', [SurveyController::class, 'getAllResponses'])->name('api.survey.responses');
    Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse'])->name('api.survey.response');
    Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse'])->name('api.survey.delete');

    // NEW SIMPLIFIED ANALYTICS API (v2) - Session-based auth for admin dashboard
    Route::get('/analytics/summary', [App\Http\Controllers\AnalyticsController::class, 'getSummary'])->name('api.analytics.summary');
    Route::get('/analytics/time-series', [App\Http\Controllers\AnalyticsController::class, 'getTimeSeries'])->name('api.analytics.time-series');
    Route::get('/analytics/compliance', [App\Http\Controllers\AnalyticsController::class, 'getCompliance'])->name('api.analytics.compliance');

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
