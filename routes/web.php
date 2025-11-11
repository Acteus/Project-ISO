<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PerformanceController;
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
// SECURITY FIX: Add rate limiting to authentication endpoints to prevent brute force attacks
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register', [StudentController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentController::class, 'register'])->name('register.post')->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/login', [StudentController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentController::class, 'login'])->name('login.post')->middleware('throttle:5,1'); // 5 attempts per minute
    Route::match(['get', 'post'], '/logout', [StudentController::class, 'logout'])->name('logout');
    Route::get('/clear-sessions', [StudentController::class, 'clearAllSessions'])->name('clear-sessions');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'verified']);
    Route::post('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update')->middleware(['auth', 'verified']);
    Route::post('/password/update', [StudentController::class, 'updatePassword'])->name('password.update')->middleware(['auth', 'verified']);
    // Consent management (GDPR & ISO 27001) - requires email verification
    Route::get('/consent/required', [StudentController::class, 'showConsentRequired'])->name('consent.required')->middleware(['auth', 'verified']);
    Route::post('/consent/accept', [StudentController::class, 'acceptConsent'])->name('consent.accept')->middleware(['auth', 'verified']);
    Route::post('/consent/revoke', [StudentController::class, 'revokeConsent'])->name('consent.revoke')->middleware(['auth', 'verified']);
});

// Email verification routes
Route::prefix('email')->name('verification.')->group(function () {
    Route::get('/verify', [StudentController::class, 'showVerificationNotice'])->middleware('auth')->name('notice');
    Route::get('/verify/{id}/{hash}', [StudentController::class, 'verifyEmail'])->middleware(['signed'])->name('verify');
    Route::post('/verification-notification', [StudentController::class, 'resendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('send');
    Route::post('/check-and-continue', [StudentController::class, 'checkVerificationAndContinue'])->middleware('auth')->name('check');
});

// Password Reset Routes
// SECURITY FIX: Add rate limiting to password reset endpoints
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email')->middleware('throttle:3,1'); // 3 attempts per minute
    Route::get('/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [ForgotPasswordController::class, 'reset'])->name('update')->middleware('throttle:3,1'); // 3 attempts per minute
});

// Admin routes - protected by EnsureAdmin middleware
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/dashboard/updates', [StudentController::class, 'checkDashboardUpdates'])->name('dashboard.updates');
    Route::get('/responses', [StudentController::class, 'allResponses'])->name('responses');
    Route::get('/responses/{id}', [StudentController::class, 'viewResponse'])->name('response.view');
    Route::get('/audit-logs', [StudentController::class, 'auditLogs'])->name('audit.logs');
    
    // Analytics view route
    Route::get('/analytics', [StudentController::class, 'showAnalytics'])->name('analytics');

    // Report Management Routes
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::post('/reports/send-weekly', [App\Http\Controllers\Admin\ReportController::class, 'sendWeeklyReport'])->name('reports.send-weekly');
    Route::post('/reports/send-monthly', [App\Http\Controllers\Admin\ReportController::class, 'sendMonthlyReport'])->name('reports.send-monthly');
    Route::post('/reports/preview-weekly', [App\Http\Controllers\Admin\ReportController::class, 'previewWeeklyReport'])->name('reports.preview-weekly');
    Route::post('/reports/preview-monthly', [App\Http\Controllers\Admin\ReportController::class, 'previewMonthlyReport'])->name('reports.preview-monthly');
    Route::post('/reports/test-email', [App\Http\Controllers\Admin\ReportController::class, 'testEmail'])->name('reports.test-email');
    Route::post('/reports/generate-metrics', [App\Http\Controllers\Admin\ReportController::class, 'generateMetrics'])->name('reports.generate-metrics');

    // Goal Management Routes
    Route::resource('goals', App\Http\Controllers\Admin\GoalController::class);
    Route::post('goals/{goal}/progress', [App\Http\Controllers\Admin\GoalController::class, 'updateProgress'])->name('goals.update-progress');

    // AI Insights Routes
    Route::get('/ai-insights', [StudentController::class, 'aiInsights'])->name('ai.insights');

    // Performance Monitoring Routes
    Route::get('/performance', [PerformanceController::class, 'showDashboard'])->name('performance.dashboard');

    // QR Code Management Routes
    // Note: Specific routes must come BEFORE resource routes to avoid conflicts
    Route::get('qr-codes/export', [QrCodeController::class, 'export'])->name('qr-codes.export');
    Route::get('qr-codes/statistics', [QrCodeController::class, 'statistics'])->name('qr-codes.statistics');
    Route::post('qr-codes/batch-generate', [QrCodeController::class, 'batchGenerate'])->name('qr-codes.batch-generate');
    Route::get('qr-codes/{id}/download', [QrCodeController::class, 'download'])->name('qr-codes.download');
    Route::resource('qr-codes', QrCodeController::class);

    // Indirect Metrics Management Routes
    Route::get('indirect-metrics', [App\Http\Controllers\Admin\IndirectMetricsController::class, 'index'])->name('indirect-metrics.index');
    Route::post('indirect-metrics/upload-csv', [App\Http\Controllers\Admin\IndirectMetricsController::class, 'uploadCsv'])->name('indirect-metrics.upload-csv');
    Route::post('indirect-metrics/update-manual', [App\Http\Controllers\Admin\IndirectMetricsController::class, 'updateManual'])->name('indirect-metrics.update-manual');
    Route::get('indirect-metrics/template', [App\Http\Controllers\Admin\IndirectMetricsController::class, 'downloadTemplate'])->name('indirect-metrics.template');
});

// Survey routes (protected by email verification)
Route::get('/survey', [SurveyController::class, 'showForm'])->middleware(['auth', 'verified'])->name('survey.form');
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

// Sitemap route (for SEO and security scanning)
Route::get('/sitemap.xml', function () {
    $appUrl = env('APP_URL', 'https://survey.kwadrateam.dev');
    
    $urls = [
        route('welcome'),
        route('home'),
        route('survey.landing'),
        route('survey.about'),
        route('survey.privacy'),
        route('survey.contact'),
        route('student.login'),
        route('student.register'),
    ];
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        $xml .= "    <changefreq>weekly</changefreq>\n";
        $xml .= "    <priority>0.8</priority>\n";
        $xml .= "  </url>\n";
    }
    
    $xml .= '</urlset>';
    
    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=utf-8');
})->name('sitemap');

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

// Debug routes - only available in non-production environments
if (!app()->environment('production')) {
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
}

// API Routes for Survey functionality
Route::prefix('api')->group(function () {
    // Public survey submission route (no auth required)
    Route::post('/survey/submit', [SurveyController::class, 'submitResponse'])->name('survey.submit');
    
    // Admin authentication routes (no auth required for login)
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    
    // Protected Admin API Routes - Session-based auth for admin dashboard
    // Note: Using only 'admin' middleware since admin auth uses session, not Auth::user()
    Route::middleware(['admin'])->group(function () {
        // Survey routes
        Route::get('/survey/analytics', [SurveyController::class, 'getAnalytics'])->name('api.survey.analytics');
        Route::get('/survey/responses', [SurveyController::class, 'getAllResponses'])->name('api.survey.responses');
        Route::get('/survey/responses/{id}', [SurveyController::class, 'getResponse'])->name('api.survey.response');
        Route::delete('/survey/responses/{id}', [SurveyController::class, 'deleteResponse'])->name('api.survey.delete');

        // NEW SIMPLIFIED ANALYTICS API (v2) - Session-based auth for admin dashboard
        Route::get('/analytics/summary', [App\Http\Controllers\AnalyticsController::class, 'getSummary'])->name('api.analytics.summary');
        Route::get('/analytics/time-series', [App\Http\Controllers\AnalyticsController::class, 'getTimeSeries'])->name('api.analytics.time-series');
        Route::get('/analytics/compliance', [App\Http\Controllers\AnalyticsController::class, 'getCompliance'])->name('api.analytics.compliance');

        // Admin authentication routes (protected)
        Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
        Route::get('/admin/me', [AdminAuthController::class, 'me']);

        // AI-powered analytics routes
        // SECURITY FIX: Add rate limiting to AI service endpoints to prevent abuse
        Route::post('/ai/predict-compliance', [AIController::class, 'predictCompliance'])->middleware('throttle:30,1');
        Route::post('/ai/cluster-responses', [AIController::class, 'clusterResponses'])->middleware('throttle:30,1');
        Route::post('/ai/analyze-sentiment', [AIController::class, 'analyzeSentiment'])->middleware('throttle:30,1');
        Route::post('/ai/extract-keywords', [AIController::class, 'extractKeywords'])->middleware('throttle:30,1');
        Route::get('/ai/compliance-risk-meter', [AIController::class, 'getComplianceRiskMeter'])->middleware('throttle:30,1');

        // AI Service Status and Analysis routes (session-based auth for admin dashboard)
        // SECURITY FIX: Add rate limiting to prevent abuse
        Route::get('/ai/service-status', [AIController::class, 'getServiceStatus'])->middleware('throttle:60,1'); // 60 requests per minute
        Route::get('/ai/metrics', [AIController::class, 'getAIMetrics'])->middleware('throttle:60,1'); // 60 requests per minute
        Route::post('/ai/analyze/{type}', [AIController::class, 'runAnalysis'])->middleware('throttle:30,1'); // 30 requests per minute

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

        // Weekly Progress Tracking Routes (for admin dashboard)
        Route::get('/visualizations/weekly-progress', [VisualizationController::class, 'getWeeklyProgressData']);
        Route::get('/visualizations/goal-progress', [VisualizationController::class, 'getGoalProgressData']);
        Route::get('/visualizations/weekly-comparison', [VisualizationController::class, 'getWeeklyComparisonData']);
        Route::get('/visualizations/monthly-report', [VisualizationController::class, 'getMonthlyReportData']);
        Route::get('/visualizations/progress-alerts', [VisualizationController::class, 'getProgressAlerts']);

        // AI Analytics routes
        // SECURITY FIX: Add rate limiting to prevent abuse
        Route::get('/ai/sentiment-analysis', [AIController::class, 'analyzeSentiment'])->middleware('throttle:30,1'); // 30 requests per minute
    });
});
