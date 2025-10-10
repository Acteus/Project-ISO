<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\VisualizationController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Student authentication routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/register', [StudentController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [StudentController::class, 'register'])->name('register.post');
    Route::get('/login', [StudentController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentController::class, 'login'])->name('login.post');
    Route::post('/logout', [StudentController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
});

// Survey routes
Route::get('/survey', function () {
    return view('survey.form');
})->name('survey.form');

Route::get('/thank-you', function () {
    return view('survey.thankyou');
})->name('survey.thankyou');

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
