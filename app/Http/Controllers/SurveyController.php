<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Services\AuditService;
use App\Services\ConsentService;
use App\Services\AnonymizationService;
use App\Services\DataMinimizationService;
use App\Services\InputSanitizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SurveyController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }
    /**
     * Show the survey landing page
     */
    public function landing(Request $request)
    {
        // Regenerate session if marked to do so (after login)
        // This should preserve authentication state automatically, but we'll verify it
        if ($request->session()->has('_should_regenerate')) {
            $request->session()->forget('_should_regenerate');
            
            // Store current authentication state before regeneration as a safety measure
            $currentUser = Auth::user();
            $currentAdmin = session('admin');
            $userId = $currentUser ? $currentUser->id : null;
            
            // Regenerate session (this creates a new session ID but preserves data)
            $request->session()->regenerate();
            
            // Verify and re-establish authentication state if it was lost during regeneration
            // This is a safety measure to ensure users don't lose their login
            if ($userId && $currentUser && !Auth::check()) {
                // Re-authenticate the user if auth was lost
                Auth::login($currentUser, false); // false = don't remember
                Log::warning('Auth state lost during session regeneration, restored', [
                    'user_id' => $userId,
                ]);
            }
            
            // Re-establish admin session if it existed and was lost
            // SECURITY FIX: Ensure admin session is stored as array format
            if ($currentAdmin && !session('admin')) {
                // Convert to array format if it's an object
                if (is_object($currentAdmin)) {
                    session(['admin' => [
                        'id' => $currentAdmin->id,
                        'name' => $currentAdmin->name,
                        'username' => $currentAdmin->username ?? null,
                        'email' => $currentAdmin->email ?? null,
                    ]]);
                } else {
                    session(['admin' => $currentAdmin]);
                }
                Log::warning('Admin session lost during regeneration, restored');
            }
        }

        // Clean up old sessions occasionally (1% chance per request)
        if (rand(1, 100) === 1) {
            $this->cleanupOldSessions();
        }

        // Explicitly check authentication status
        $user = Auth::user();
        $admin = session('admin');

        // Log authentication status for debugging
        Log::info('Landing page accessed', [
            'authenticated' => Auth::check(),
            'user_id' => $user ? $user->id : null,
            'admin' => $admin ? true : false,
        ]);

        // Check consent for authenticated students (GDPR & ISO 27001 requirement)
        if ($user && $user->role === 'student') {
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;
            
            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                // User doesn't have valid consent, redirect to consent page
                return redirect()->route('student.consent.required')
                    ->with('info', 'Please provide consent before accessing the survey.');
            }
        }

        return view('survey.landing', [
            'user' => $user,
            'admin' => $admin,
        ]);
    }

    /**
     * Clean up expired sessions from database
     */
    private function cleanupOldSessions()
    {
        try {
            // Delete sessions older than 24 hours
            $expiredTime = now()->subHours(24)->timestamp;
            DB::table('sessions')
                ->where('last_activity', '<', $expiredTime)
                ->delete();
        } catch (\Exception $e) {
            Log::warning('Session cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the survey form
     */
    public function showForm()
    {
        // Check consent for authenticated students (GDPR & ISO 27001 requirement)
        $user = Auth::user();
        if ($user && $user->role === 'student') {
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;
            
            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                // User doesn't have valid consent, redirect to consent page
                return redirect()->route('student.consent.required')
                    ->with('info', 'Please provide consent before taking the survey.');
            }
        }

        // Check if the authenticated user already has a previous response
        $hasPreviousResponse = false;
        if ($user && $user->student_id) {
            try {
                $encryptionService = app(\App\Services\EncryptionService::class);
                $encryptedStudentId = $encryptionService->encrypt($user->student_id);
                $hasPreviousResponse = SurveyResponse::where('student_id', $encryptedStudentId)->exists();
            } catch (\Exception $e) {
                Log::warning('Unable to check previous survey response', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id ?? null,
                ]);
            }
        }

        return view('survey.form', [
            'hasPreviousResponse' => $hasPreviousResponse,
        ]);
    }

    public function submitResponse(Request $request)
    {
        // Check if user is authenticated (works for both web and API routes)
        $isAuthenticated = Auth::check() || Auth::guard('sanctum')->check() || session()->has('admin');

        // Debug logging
        Log::info('Survey submission attempt', [
            'auth_check' => Auth::check(),
            'sanctum_check' => Auth::guard('sanctum')->check(),
            'session_admin' => session()->has('admin'),
            'has_student_id_in_request' => $request->has('student_id'),
            'student_id_value' => $request->input('student_id', 'NOT_PROVIDED'),
        ]);

        // Check if authenticated user already has valid consent
        // If they do, automatically set consent_given to true (consent was given during registration)
        $consentService = app(ConsentService::class);
        $hasValidConsent = false;
        $studentId = $request->input('student_id');
        
        // Try to get student_id from authenticated user if not in request
        if (!$studentId) {
            if (Auth::check() && Auth::user()->student_id) {
                $studentId = Auth::user()->student_id;
            } elseif (Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->student_id) {
                $studentId = Auth::guard('sanctum')->user()->student_id;
            } elseif (session()->has('admin')) {
                // Admin doesn't have student_id - this check was incorrect
                // Admins submitting surveys would need to provide student_id explicitly
                $admin = session('admin');
                // Handle both array and object formats, but admins don't have student_id
                // This branch should not normally execute for admin users
            }
        }
        
        // Check if user has valid consent
        if ($studentId) {
            $hasValidConsent = $consentService->hasValidConsent($studentId, 'survey_response');
            
            // If user has valid consent, automatically set consent_given to true
            if ($hasValidConsent) {
                $request->merge(['consent_given' => true]);
                Log::info('User has valid consent from registration, auto-setting consent_given', [
                    'student_id' => $studentId ? '***REDACTED***' : null,
                ]);
            }
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'nullable|string', // Always nullable, we'll handle authentication below
            'track' => 'required|in:CSS',
            'grade_level' => 'required|integer|in:11,12',
            'academic_year' => 'required|string|max:9',
            'semester' => 'required|in:1st,2nd',
            // ISO 21001 Learner Needs Assessment (1-5 scale)
            'curriculum_relevance_rating' => 'required|integer|min:1|max:5',
            'learning_pace_appropriateness' => 'required|integer|min:1|max:5',
            'individual_support_availability' => 'required|integer|min:1|max:5',
            'learning_style_accommodation' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            'teaching_quality_rating' => 'required|integer|min:1|max:5',
            'learning_environment_rating' => 'required|integer|min:1|max:5',
            'peer_interaction_satisfaction' => 'required|integer|min:1|max:5',
            'extracurricular_satisfaction' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Success Indicators (1-5 scale)
            'academic_progress_rating' => 'required|integer|min:1|max:5',
            'skill_development_rating' => 'required|integer|min:1|max:5',
            'critical_thinking_improvement' => 'required|integer|min:1|max:5',
            'problem_solving_confidence' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Safety Assessment (1-5 scale)
            'physical_safety_rating' => 'required|integer|min:1|max:5',
            'psychological_safety_rating' => 'required|integer|min:1|max:5',
            'bullying_prevention_effectiveness' => 'required|integer|min:1|max:5',
            'emergency_preparedness_rating' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            'mental_health_support_rating' => 'required|integer|min:1|max:5',
            'stress_management_support' => 'required|integer|min:1|max:5',
            'physical_health_support' => 'required|integer|min:1|max:5',
            'overall_wellbeing_rating' => 'required|integer|min:1|max:5',
            // Overall Satisfaction and Feedback
            'overall_satisfaction' => 'required|integer|min:1|max:5',
            'positive_aspects' => 'nullable|string|max:1000',
            'improvement_suggestions' => 'nullable|string|max:1000',
            'additional_comments' => 'nullable|string|max:1000',
            // Additional Feedback Fields (ISO 21001 compliant)
            'feedback_taken_seriously' => 'nullable|integer|min:1|max:5',
            'school_responsiveness' => 'nullable|integer|min:1|max:5',
            'visible_improvements' => 'nullable|integer|min:1|max:5',
            // Demographics
            'gender' => 'nullable|string|in:Male,Female,Non-binary,Prefer not to say',
            // Consent
            'consent_given' => 'required|boolean|accepted',
            // Indirect metrics are optional for now
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'grade_average' => 'nullable|numeric|min:0|max:4.0',
            'participation_score' => 'nullable|integer|min:0|max:100',
            'extracurricular_hours' => 'nullable|integer|min:0',
            'counseling_sessions' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            // Log validation failure for audit (ISO 21001:8.2.4 - Traceability)
            Log::warning('ISO 21001 Survey submission validation failed', [
                'ip' => $request->ip(),
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Sanitize input data to prevent XSS attacks
        $sanitizationService = app(InputSanitizationService::class);
        $data = $sanitizationService->sanitizeSurveyData($data);

        // Enforce data minimization - only essential ISO 21001 metrics (GDPR & ISO 27001 compliant)
        $minimizationService = app(DataMinimizationService::class);
        $minimizationCheck = $minimizationService->validateDataMinimization($data);
        
        if (!$minimizationCheck['valid']) {
            Log::warning('Data minimization violation detected', [
                'rejected_fields' => $minimizationCheck['rejected_fields'],
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'message' => 'Invalid data fields detected',
                'errors' => [
                    'data_minimization' => 'Only essential ISO 21001 metrics are allowed. Rejected fields: ' . implode(', ', $minimizationCheck['rejected_fields'])
                ]
            ], 422);
        }

        // Filter to only allowed fields
        $data = $minimizationService->filterAllowedFields($data);

        // Validate and record explicit consent (GDPR & ISO 27001 compliant)
        $consentService = app(ConsentService::class);
        try {
            $consentService->validateAndRecordConsent(
                (bool) ($data['consent_given'] ?? false),
                $data['student_id'] ?? null,
                $request->ip(),
                [
                    'purpose' => 'survey_response',
                    'consent_version' => '1.0',
                    'survey_track' => $data['track'] ?? null,
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Survey submission rejected due to consent', [
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Consent is required to submit this survey',
                'error' => $e->getMessage()
            ], 403);
        }

        // Determine student_id from multiple sources
        // Only generate anonymous ID if student_id is truly not provided (null or empty string after trim)
        if (!isset($data['student_id']) || (is_string($data['student_id']) && trim($data['student_id']) === '')) {
            // Try to get from authenticated user
            if (Auth::check() && Auth::user()->student_id) {
                $data['student_id'] = Auth::user()->student_id;
            } elseif (Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->student_id) {
                $data['student_id'] = Auth::guard('sanctum')->user()->student_id;
            } elseif (session()->has('admin')) {
                // Admin doesn't have student_id - admins should not submit surveys as students
                // This is a security consideration: admins should use student accounts for survey submission
                // For now, generate anonymous ID if admin is submitting
                $data['student_id'] = 'ADMIN_' . uniqid() . '_' . substr(md5($request->ip() . time()), 0, 8);
            } else {
                // Generate anonymous ID if no student_id provided
                $data['student_id'] = 'ANON_' . uniqid() . '_' . substr(md5($request->ip() . time()), 0, 8);
            }
        }

        // Let model mutators handle encryption - don't encrypt in controller to avoid double encryption
        $data['ip_address'] = $request->ip();

        $response = SurveyResponse::create($data);

        // Log successful submission for audit trail (ISO 21001:8.2.4)
        $description = Auth::check() && Auth::user()->role === 'admin'
            ? 'Admin processed ISO 21001 survey response submission'
            : (Auth::check() ? 'Student submitted ISO 21001 survey response' : 'Submitted ISO 21001 survey response (anonymous)');

        $this->auditService->logDataModification(
            'survey_response',
            $response->id,
            'create',
            null,
            [
                'response_id' => $response->id,
                'student_id' => $data['student_id'] ?? 'anonymous',
                'track' => $response->track,
                'grade_level' => $response->grade_level,
            ],
            $request
        );

        return response()->json([
            'message' => 'Survey response submitted successfully',
            'data' => $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address'])
        ], 201);
    }

    public function getAnalytics(Request $request)
    {
        // Validate and sanitize query parameters
        $validator = Validator::make($request->query(), [
            'track' => 'nullable|in:CSS',
            'grade_level' => 'nullable|integer|in:11,12',
            'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$|^\d{4}$/',
            'semester' => 'nullable|in:1st,2nd',
            'date_from' => 'nullable|date|date_format:Y-m-d',
            'date_to' => 'nullable|date|date_format:Y-m-d|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $sanitizationService = app(InputSanitizationService::class);
        $track = $sanitizationService->sanitizeQueryParameter($request->query('track'), 'track');
        $gradeLevel = $sanitizationService->sanitizeQueryParameter($request->query('grade_level'), 'grade_level');
        $academicYear = $sanitizationService->sanitizeQueryParameter($request->query('academic_year'), 'academic_year');
        $semester = $sanitizationService->sanitizeQueryParameter($request->query('semester'), 'semester');
        $dateFrom = $sanitizationService->sanitizeQueryParameter($request->query('date_from'), 'date');
        $dateTo = $sanitizationService->sanitizeQueryParameter($request->query('date_to'), 'date');

        // Log analytics access for audit (ISO 21001:8.2.4 - Data access traceability)
        // Check for admin session (session-based admin authentication)
        $admin = session('admin');
        if ($admin) {
            $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
            if ($adminId) {
                \App\Models\AuditLog::create([
                    'admin_id' => $adminId,
                    'action' => 'view_analytics',
                    'description' => 'Accessed analytics API',
                    'ip_address' => $request->ip(),
                    'new_values' => [
                        'query_params' => $request->query(),
                    ],
                ]);
            }
        }

        // Generate cache key based on query parameters
        $cacheKey = 'analytics:' . md5(json_encode([
            'track' => $track,
            'grade_level' => $gradeLevel,
            'academic_year' => $academicYear,
            'semester' => $semester,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]));

        // Cache analytics data using CacheService for consistent caching strategy
        $analytics = \App\Services\CacheService::remember($cacheKey, function () use ($track, $gradeLevel, $academicYear, $semester, $dateFrom, $dateTo) {
            return $this->calculateAnalytics($track, $gradeLevel, $academicYear, $semester, $dateFrom, $dateTo);
        }, 'analytics');

        // Determine response format based on request type (after caching)
        if ($request->header('Accept') === 'application/json' || $request->wantsJson()) {
            if ($analytics === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'No survey responses found',
                    'data' => [
                        'total_responses' => 0,
                        'iso_21001_indices' => [
                            'learner_needs_index' => 0.00,
                            'satisfaction_score' => 0.00,
                            'success_index' => 0.00,
                            'safety_index' => 0.00,
                            'wellbeing_index' => 0.00,
                            'overall_satisfaction' => 0.00,
                        ],
                        'indirect_metrics' => [
                            'average_grade' => 0.00,
                            'average_attendance_rate' => 0.00,
                            'average_participation_score' => 0.00,
                            'average_extracurricular_hours' => 0.00,
                            'average_counseling_sessions' => 0.00,
                        ],
                        'correlation_analysis' => [
                            'satisfaction_vs_performance_correlation' => 0.00,
                            'satisfaction_vs_attendance_correlation' => 0.00,
                            'safety_vs_attendance_correlation' => 0.00,
                            'wellbeing_vs_counseling_correlation' => 0.00,
                        ],
                        'distribution' => [
                            'track' => [],
                            'grade_level' => [],
                            'academic_year' => [],
                            'semester' => [],
                        ],
                        'consent_rate' => 0.00,
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'ISO 21001 Analytics retrieved successfully',
                'data' => $analytics
            ]);
        }

        // Return HTML view - Using new simplified dashboard
        return view('analytics.dashboard', [
            'analytics' => $analytics,
            'noData' => $analytics === null
        ]);
    }

    private function calculateAnalytics($track, $gradeLevel, $academicYear, $semester, $dateFrom, $dateTo)
    {
        $query = SurveyResponse::query();

        if ($track) {
            $query->where('track', $track);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        // Date range filtering
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $responses = $query->get();

        if ($responses->isEmpty()) {
            // Return null to indicate no data
            return null;
        }

        // ISO 21001 Composite Scores
        $learnerNeedsIndex = round(
            ($responses->avg('curriculum_relevance_rating') +
             $responses->avg('learning_pace_appropriateness') +
             $responses->avg('individual_support_availability') +
             $responses->avg('learning_style_accommodation')) / 4, 2
        );

        $satisfactionScore = round(
            ($responses->avg('teaching_quality_rating') +
             $responses->avg('learning_environment_rating') +
             $responses->avg('peer_interaction_satisfaction') +
             $responses->avg('extracurricular_satisfaction')) / 4, 2
        );

        $successIndex = round(
            ($responses->avg('academic_progress_rating') +
             $responses->avg('skill_development_rating') +
             $responses->avg('critical_thinking_improvement') +
             $responses->avg('problem_solving_confidence')) / 4, 2
        );

        $safetyIndex = round(
            ($responses->avg('physical_safety_rating') +
             $responses->avg('psychological_safety_rating') +
             $responses->avg('bullying_prevention_effectiveness') +
             $responses->avg('emergency_preparedness_rating')) / 4, 2
        );

        $wellbeingIndex = round(
            ($responses->avg('mental_health_support_rating') +
             $responses->avg('stress_management_support') +
             $responses->avg('physical_health_support') +
             $responses->avg('overall_wellbeing_rating')) / 4, 2
        );

        // Performance vs Satisfaction Correlation
        $avgSatisfaction = round($responses->avg('overall_satisfaction'), 2);
        $avgGrade = $responses->avg('grade_average') ?? 0;
        $avgAttendance = $responses->avg('attendance_rate') ?? 0;

        // Get date range from responses
        $oldestResponse = $responses->min('created_at');
        $newestResponse = $responses->max('created_at');

        $analytics = [
            'total_responses' => $responses->count(),
            'date_range' => [
                'oldest' => $oldestResponse,
                'newest' => $newestResponse,
            ],
            'iso_21001_indices' => [
                'learner_needs_index' => $learnerNeedsIndex,
                'satisfaction_score' => $satisfactionScore,
                'success_index' => $successIndex,
                'safety_index' => $safetyIndex,
                'wellbeing_index' => $wellbeingIndex,
                'overall_satisfaction' => $avgSatisfaction,
            ],
            'indirect_metrics' => [
                'average_grade' => round($avgGrade, 2),
                'average_attendance_rate' => round($avgAttendance, 2),
                'average_participation_score' => round($responses->avg('participation_score') ?? 0, 2),
                'average_extracurricular_hours' => round($responses->avg('extracurricular_hours') ?? 0, 2),
                'average_counseling_sessions' => round($responses->avg('counseling_sessions') ?? 0, 2),
            ],
            'correlation_analysis' => [
                'satisfaction_vs_performance_correlation' => round($avgSatisfaction * $avgGrade, 2),
                'satisfaction_vs_attendance_correlation' => round($avgSatisfaction * $avgAttendance, 2),
                'safety_vs_attendance_correlation' => round($safetyIndex * $avgAttendance, 2),
                'wellbeing_vs_counseling_correlation' => round($wellbeingIndex * ($responses->avg('counseling_sessions') ?? 0), 2),
            ],
            'distribution' => [
                'track' => $responses->groupBy('track')->map->count(),
                'grade_level' => $responses->groupBy('grade_level')->map->count(),
                'academic_year' => $responses->groupBy('academic_year')->map->count(),
                'semester' => $responses->groupBy('semester')->map->count(),
            ],
            'consent_rate' => round(($responses->where('consent_given', true)->count() / $responses->count()) * 100, 2),
        ];

        // Return the analytics data array
        return $analytics;
    }

    public function getAllResponses(Request $request)
    {
        // Validate and sanitize query parameters
        $validator = Validator::make($request->query(), [
            'per_page' => 'nullable|integer|min:1|max:100',
            'track' => 'nullable|in:CSS',
            'grade_level' => 'nullable|integer|in:11,12',
            'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$|^\d{4}$/',
            'semester' => 'nullable|in:1st,2nd',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Log data access for ISO 21001:8.2.4 compliance
        $this->auditService->logDataAccess(
            'survey_response',
            null,
            'list',
            $request,
            [
                'filters' => $request->query(),
            ]
        );

        $sanitizationService = app(InputSanitizationService::class);
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage)); // Ensure per_page is between 1 and 100
        $track = $sanitizationService->sanitizeQueryParameter($request->query('track'), 'track');
        $gradeLevel = $sanitizationService->sanitizeQueryParameter($request->query('grade_level'), 'grade_level');
        $academicYear = $sanitizationService->sanitizeQueryParameter($request->query('academic_year'), 'academic_year');
        $semester = $sanitizationService->sanitizeQueryParameter($request->query('semester'), 'semester');

        $query = SurveyResponse::query();

        if ($track) {
            $query->where('track', $track);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        $responses = $query->paginate($perPage);

        // Add anonymous IDs for privacy (GDPR & ISO 27001 compliant)
        $anonymizationService = app(AnonymizationService::class);
        $responses->getCollection()->transform(function ($response) use ($anonymizationService) {
            // Use anonymization service for analytics
            if ($anonymizationService->useForAnalytics()) {
                $response->anonymous_id = $response->anonymous_id;
            }
            return $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address']);
        });

        return response()->json([
            'message' => 'Survey responses retrieved successfully',
            'data' => $responses
        ]);
    }

    public function getResponse($id, Request $request)
    {
        $response = SurveyResponse::findOrFail($id);

        // Log data access for ISO 21001:8.2.4 compliance
        $this->auditService->logDataAccess(
            'survey_response',
            $id,
            'view',
            $request
        );

        // Ensure sensitive fields are hidden and add anonymous_id
        $sanitizedResponse = $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address']);
        $sanitizedResponse->anonymous_id = $response->anonymous_id;

        return response()->json([
            'message' => 'Survey response retrieved successfully',
            'data' => $sanitizedResponse
        ]);
    }

    public function deleteResponse($id, Request $request)
    {
        $response = SurveyResponse::findOrFail($id);
        $oldValues = $response->toArray();

        // Log the deletion with full traceability
        $this->auditService->logDataModification(
            'survey_response',
            $id,
            'delete',
            $oldValues,
            null,
            $request
        );

        $response->delete();

        return response()->json([
            'message' => 'Survey response deleted successfully'
        ]);
    }
}
