<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function showRegistrationForm()
    {
        return view('student.register');
    }

    public function register(Request $request)
    {
        // SECURITY FIX: Enhanced password strength requirements
        // Password must contain: uppercase, lowercase, number, and special character
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'year' => 'required|in:11,12',
            'section' => 'required|string|max:10',
            'studentid' => 'required|string|unique:users,student_id',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/', // at least one lowercase letter
                'regex:/[A-Z]/', // at least one uppercase letter
                'regex:/[0-9]/', // at least one digit
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
            'acknowledge' => 'required|accepted',
        ], [
            'email.unique' => 'This email is already registered.',
            'studentid.unique' => 'This student ID is already registered.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user account for student
        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'student_id' => $request->studentid,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'year_level' => $request->year,
            'section' => $request->section,
        ]);

        // Log registration for audit trail using AuditService
        $auditService = app(\App\Services\AuditService::class);
        $auditService->logDataModification(
            'user',
            $user->id,
            'create',
            null,
            [
                'user_type' => 'student',
                'student_id' => $user->student_id,
                'name' => $user->name,
                'year_level' => $user->year_level,
                'section' => $user->section,
            ],
            $request
        );

        // Log the student in
        Auth::login($user);

        // Mark that we should regenerate on next request
        $request->session()->put('_should_regenerate', true);

        // By default send verification email and redirect to verification notice.
        // For local development or when explicitly configured, auto-verify to simplify testing.
        $skipVerification = env('SKIP_EMAIL_VERIFICATION', false) || app()->environment('local');

        if ($skipVerification) {
            try {
                // Mark user as verified for local/testing environments
                $user->markEmailAsVerified();
                Log::info('Auto-verified email for local/testing: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to auto-verify email: ' . $e->getMessage());
            }

            $redirect = route('survey.landing');
            $message = 'Registration successful! You have been auto-verified for local testing.';
        } else {
            try {
                $user->sendEmailVerificationNotification();
                Log::info('Email verification sent to: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            $redirect = route('verification.notice');
            $message = 'Registration successful! Please check your email to verify your account.';
        }

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
            'user' => [
                'name' => $user->name,
                'student_id' => $user->student_id,
                'year_level' => $user->year_level,
                'section' => $user->section,
                'email' => $user->email,
            ]
        ]);
    }

    public function showLoginForm()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if this is an admin login attempt
        // Admins can login with either username or email
        $admin = \App\Models\Admin::where(function($query) use ($request) {
            $query->where('username', $request->student_id)
                  ->orWhere('email', $request->student_id);
        })->first();

        if ($admin && \Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            // SECURITY FIX: Store minimal admin data in session as array (not full object)
            // This reduces session size and improves security
            session(['admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
            ]]);

            // Regenerate session ID to prevent session fixation
            $request->session()->regenerate();

            // Log admin login for audit trail using AuditService
            $auditService = app(\App\Services\AuditService::class);
            $auditService->logAuthentication('login', true, $request, [
                'user_type' => 'admin',
                'admin_id' => $admin->id,
            ]);

            return response()->json([
                'message' => 'Admin login successful! Welcome back.',
                'redirect' => route('admin.dashboard'),
                'user' => [
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'role' => 'admin',
                ]
            ]);
        }

        // If admin lookup found a user but password was wrong, return error
        // Don't reveal whether it's an admin account for security
        if ($admin) {
            return response()->json([
                'message' => 'Invalid credentials. Please check your username and password.'
            ], 401);
        }

        // Regular student login - check if input is email or student ID
        $loginField = filter_var($request->student_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_id';

        $credentials = [
            $loginField => $request->student_id,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // DON'T regenerate session on AJAX login - it causes the browser to not pick up the new session ID
            // The session will be automatically regenerated on the next page load by Laravel
            // $request->session()->regenerate();

            // Mark that we should regenerate on next request
            $request->session()->put('_should_regenerate', true);

            // Debug logging
            Log::info('Login successful', [
                'user_id' => $user->id,
                'session_id' => $request->session()->getId(),
                'authenticated' => Auth::check(),
                'session_data' => $request->session()->all(),
            ]);

            // Log login for audit trail using AuditService
            $auditService = app(\App\Services\AuditService::class);
            $auditService->logAuthentication('login', true, $request, [
                'user_type' => 'student',
                'user_id' => $user->id,
            ]);

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    'message' => 'Please verify your email address before accessing the survey.',
                    'redirect' => route('verification.notice'),
                    'user' => [
                        'name' => $user->name,
                        'student_id' => $user->student_id,
                        'email_verified' => false,
                    ]
                ]);
            }

            // Check if user has valid consent (GDPR & ISO 27001 requirement)
            // Existing users who registered before consent feature need to provide consent
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;

            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                return response()->json([
                    'message' => 'Please provide consent to continue using the survey system.',
                    'redirect' => route('student.consent.required'),
                    'user' => [
                        'name' => $user->name,
                        'student_id' => $user->student_id,
                        'year_level' => $user->year_level,
                        'section' => $user->section,
                    ]
                ]);
            }

            return response()->json([
                'message' => 'Login successful! Welcome back.',
                'redirect' => route('survey.landing'),
                'user' => [
                    'name' => $user->name,
                    'student_id' => $user->student_id,
                    'year_level' => $user->year_level,
                    'section' => $user->section,
                ]
            ]);
        }

        // Generic error message for security (don't reveal if username exists)
        return response()->json([
            'message' => 'Invalid credentials. Please check your username and password.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $admin = session('admin');

        // Log logout for audit trail using AuditService
        $auditService = app(\App\Services\AuditService::class);
        if ($user) {
            $auditService->logAuthentication('logout', true, $request, [
                'user_type' => 'student',
                'user_id' => $user->id,
            ]);
        }
        if ($admin) {
            $auditService->logAuthentication('logout', true, $request, [
                'user_type' => 'admin',
                'user_id' => $admin->id,
            ]);
        }

        // Clear both student and admin sessions
        Auth::logout();
        $request->session()->forget('admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Always return JSON for modal-based logout
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Logged out successfully',
                'redirect' => route('student.login')
            ]);
        }

        // For non-AJAX requests, redirect to login with message
        return redirect()->route('student.login')->with('success', 'Logged out successfully');
    }

    /**
     * Force clear all user sessions (useful when stuck with old cookies)
     */
    public function clearAllSessions(Request $request)
    {
        try {
            // Get current session ID before clearing
            $currentSessionId = $request->session()->getId();

            // Log out from current session
            Auth::logout();
            $request->session()->forget('admin');

            // Delete ALL sessions for this user from database if authenticated
            if (Auth::check()) {
                $userId = Auth::id();
                DB::table('sessions')
                    ->where('user_id', $userId)
                    ->delete();
            }

            // Invalidate current session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('student.login')->with('success', 'All sessions cleared successfully. Please log in again.');
        } catch (\Exception $e) {
            Log::error('Failed to clear all sessions: ' . $e->getMessage());
            return redirect()->route('student.login')->with('error', 'Failed to clear sessions. Please try clearing your browser cookies.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        // Check if user has valid consent (GDPR & ISO 27001 requirement)
        // Existing users who registered before consent feature need to provide consent
        $consentService = app(\App\Services\ConsentService::class);
        $studentId = $user->student_id ?? null;

        if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
            // User doesn't have valid consent, redirect to consent page
            return redirect()->route('student.consent.required')
                ->with('info', 'Please provide consent to continue using the survey system.');
        }

        return view('student.dashboard', compact('user'));
    }

    /**
     * Show consent required page for existing users
     *
     * This page is shown to users who registered before the consent feature was added
     * or users who haven't provided consent yet
     */
    public function showConsentRequired()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        $consentService = app(\App\Services\ConsentService::class);
        $studentId = $user->student_id ?? null;

        // If user already has valid consent, redirect to dashboard
        if ($studentId && $consentService->hasValidConsent($studentId, 'survey_response')) {
            return redirect()->route('student.dashboard')
                ->with('success', 'You already have active consent.');
        }

        return view('student.consent-required', compact('user'));
    }

    /**
     * Accept consent (GDPR & ISO 27001 compliant)
     *
     * Handles consent acceptance from existing users
     */
    public function acceptConsent(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        $validator = Validator::make($request->all(), [
            'consent_given' => 'required|accepted',
        ], [
            'consent_given.required' => 'You must provide consent to continue.',
            'consent_given.accepted' => 'You must check the consent box to continue.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('student.consent.required')
                ->withErrors($validator)
                ->withInput();
        }

        if (!$user->student_id) {
            return redirect()->route('student.consent.required')
                ->with('error', 'Unable to record consent: Student ID not found. Please contact support.');
        }

        try {
            $consentService = app(\App\Services\ConsentService::class);

            // Record consent (method signature: bool $consentGiven, ?string $studentId, ?string $ipAddress, array $context)
            $consentGiven = $consentService->validateAndRecordConsent(
                true, // consent given
                $user->student_id,
                $request->ip(),
                [
                    'purpose' => 'survey_response',
                    'source' => 'existing_user_consent_page',
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'consent_version' => '1.0',
                ]
            );

            if ($consentGiven) {
                // Determine redirect based on where user came from
                $redirectTo = $request->input('redirect_to', route('student.dashboard'));

                return redirect($redirectTo)
                    ->with('success', 'Thank you for providing consent. You can now access all features of the survey system.');
            } else {
                return redirect()->route('student.consent.required')
                    ->with('error', 'Failed to record consent. Please try again or contact support.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to record consent', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('student.consent.required')
                ->with('error', 'An error occurred while recording consent. Please try again or contact support.');
        }
    }

    /**
     * Revoke consent (GDPR & ISO 27001 compliant)
     *
     * Allows students to revoke their consent at any time as required by GDPR
     */
    public function revokeConsent(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        if (!$user->student_id) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to revoke consent: Student ID not found.');
        }

        try {
            $consentService = app(\App\Services\ConsentService::class);
            $revoked = $consentService->revokeConsent(
                $user->student_id,
                'survey_response',
                $request->ip()
            );

            if ($revoked) {
                // Log the revocation for audit trail
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'consent_revoked_by_student',
                    'description' => 'Student revoked consent for survey data processing',
                    'ip_address' => $request->ip(),
                    'new_values' => [
                        'student_id' => '***REDACTED***',
                        'purpose' => 'survey_response',
                        'timestamp' => now()->toIso8601String(),
                    ],
                ]);

                return redirect()->route('student.dashboard')
                    ->with('success', 'Your consent has been successfully revoked. You will need to provide consent again to submit new surveys.');
            } else {
                return redirect()->route('student.dashboard')
                    ->with('error', 'No active consent found to revoke.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to revoke consent', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('student.dashboard')
                ->with('error', 'An error occurred while revoking consent. Please try again or contact support.');
        }
    }

    /**
     * Show the email verification notice.
     */
    public function showVerificationNotice()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('student.login');
        }

        // If already verified, redirect to survey landing
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('survey.landing');
        }

        return view('student.verify-email');
    }

    /**
     * Verify the user's email address.
     */
    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // Check if hash matches
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('verification.notice')->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('survey.landing')->with('success', 'Email already verified!');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            // Log email verification for audit trail
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'email_verified',
                'description' => 'Student verified their email address',
                'ip_address' => $request->ip(),
            ]);

            // Log the user in if not already logged in
            if (!Auth::check()) {
                Auth::login($user);
            }

            return redirect()->route('survey.landing')->with('success', 'Email verified successfully! You can now access the survey.');
        }

        return redirect()->route('verification.notice')->with('error', 'Failed to verify email. Please try again.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerificationEmail(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        try {
            $user->sendEmailVerificationNotification();

            // Log resend for audit trail
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'verification_email_resent',
                'description' => 'Student requested verification email resend',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Verification email has been resent! Please check your inbox.'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to send verification email. Please try again later.'
            ], 500);
        }
    }

    public function adminDashboard()
    {
        // Admin is now validated and refreshed by EnsureAdmin middleware
        // The middleware passes the admin model via $request->admin
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Handle both array and object formats for backward compatibility
        $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
        if (!$adminId) {
            return redirect()->route('student.login');
        }

        // Cache dashboard data using CacheService for consistent caching strategy
        $cacheKey = 'dashboard:admin:' . $adminId;
        $dashboardData = \App\Services\CacheService::remember($cacheKey, function () {
            // Optimize queries to prevent N+1 problems
            // Use select() to only fetch needed columns
            // Use eager loading if relationships exist (none currently, but prepared for future)
            return [
                'totalResponses' => \App\Models\SurveyResponse::count(),
                // Only select needed columns to reduce memory usage
                'recentResponses' => \App\Models\SurveyResponse::select(['id', 'track', 'created_at'])
                    ->latest()
                    ->take(5)
                    ->get(),
                // Use database aggregation instead of loading all records
                'responsesByTrack' => \App\Models\SurveyResponse::selectRaw('track, COUNT(*) as count')
                    ->groupBy('track')
                    ->orderBy('count', 'desc')
                    ->get(),
            ];
        }, 'dashboard');

        // Ensure admin is passed as array for view compatibility
        $adminData = is_array($admin) ? $admin : [
            'id' => $admin->id,
            'name' => $admin->name,
            'username' => $admin->username ?? null,
            'email' => $admin->email ?? null,
        ];

        return view('admin.dashboard', array_merge(
            ['admin' => (object)$adminData], // Convert to object for view compatibility
            $dashboardData
        ));
    }

    public function viewResponse($id)
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Handle both array and object formats
        $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
        if (!$adminId) {
            return redirect()->route('student.login');
        }

        // Get the survey response
        $response = \App\Models\SurveyResponse::findOrFail($id);

        // Log viewing of response for audit trail
        AuditLog::create([
            'admin_id' => $adminId,
            'action' => 'view_survey_response',
            'description' => 'Admin viewed detailed survey response',
            'ip_address' => request()->ip(),
            'new_values' => ['response_id' => $response->id],
        ]);

        // Ensure admin is passed as object for view compatibility
        $adminData = is_array($admin) ? (object)$admin : $admin;
        return view('admin.response-detail', ['admin' => $adminData, 'response' => $response]);
    }

    public function allResponses(Request $request)
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Handle both array and object formats
        $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
        if (!$adminId) {
            return redirect()->route('student.login');
        }

        // Get all survey responses with pagination
        $responses = \App\Models\SurveyResponse::latest()->paginate(15);

        // Log viewing of all responses for audit trail
        AuditLog::create([
            'admin_id' => $adminId,
            'action' => 'view_all_responses',
            'description' => 'Admin viewed all survey responses list',
            'ip_address' => request()->ip(),
        ]);

        // Ensure admin is passed as object for view compatibility
        $adminData = is_array($admin) ? (object)$admin : $admin;
        return view('admin.all-responses', ['admin' => $adminData, 'responses' => $responses]);
    }

    public function auditLogs(Request $request)
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Get filter parameters
        $action = $request->get('action');
        $userType = $request->get('user_type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 20);

        // Build query with eager loading to prevent N+1 queries
        $query = AuditLog::with(['user'])->orderBy('created_at', 'desc');

        // Apply filters
        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        // Filter by resource type if needed (new feature)
        if ($request->has('resource_type') && $request->get('resource_type') !== 'all') {
            $query->where('resource_type', $request->get('resource_type'));
        }

        if ($userType && $userType !== 'all') {
            // Filter by user type - check metadata or description for user type
            if ($userType === 'student') {
                // Students typically have user_id and description mentions "student" or metadata has user_type=student
                $query->where(function($q) {
                    $q->where('description', 'LIKE', '%Student%')
                      ->orWhere('description', 'LIKE', '%student%')
                      ->orWhere(function($q2) {
                          // Check if it's not an admin action
                          $q2->whereNotNull('user_id')
                            ->where(function($q3) {
                                $q3->whereNull('metadata')
                                  ->orWhere('metadata', 'NOT LIKE', '%"user_type":"admin"%');
                            });
                      });
                });
            } elseif ($userType === 'admin') {
                // Admins have descriptions mentioning "Admin" or metadata with user_type=admin
                $query->where(function($q) {
                    $q->where('description', 'LIKE', '%Admin%')
                      ->orWhere('description', 'LIKE', '%admin%')
                      ->orWhere('metadata', 'LIKE', '%"user_type":"admin"%');
                });
            }
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                  ->orWhere('ip_address', 'LIKE', "%{$search}%")
                  ->orWhere('user_id', 'LIKE', "%{$search}%")
                  ->orWhere('resource_type', 'LIKE', "%{$search}%")
                  ->orWhere('resource_id', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated results
        $auditLogs = $query->paginate($perPage)->appends($request->except('page'));

        // Get unique actions for filter dropdown
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        // Get unique resource types for filter
        $resourceTypes = AuditLog::select('resource_type')
            ->distinct()
            ->whereNotNull('resource_type')
            ->orderBy('resource_type')
            ->pluck('resource_type');

        // Create user types manually
        $userTypes = collect(['student', 'admin']);

        // Get statistics for filtered results - updated for new action structure
        $baseQuery = AuditLog::query();
        if ($action && $action !== 'all') {
            $baseQuery->where('action', $action);
        }
        if ($dateFrom) {
            $baseQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $baseQuery->whereDate('created_at', '<=', $dateTo);
        }

        // Calculate stats with backward compatibility
        $stats = [
            'total' => $baseQuery->count(),
            'loginCount' => (clone $baseQuery)->where(function($q) {
                $q->where('action', 'authentication')
                  ->where('description', 'LIKE', '%login%')
                  ->orWhereIn('action', ['student_login', 'admin_login']); // Backward compatibility
            })->count(),
            'logoutCount' => (clone $baseQuery)->where(function($q) {
                $q->where('action', 'authentication')
                  ->where('description', 'LIKE', '%logout%')
                  ->orWhereIn('action', ['student_logout', 'admin_logout']); // Backward compatibility
            })->count(),
            'submissionCount' => (clone $baseQuery)->where(function($q) {
                $q->where(function($q2) {
                    $q2->where('action', 'data_modification')
                      ->where('resource_type', 'survey_response')
                      ->where('description', 'LIKE', '%survey%');
                })
                ->orWhere('action', 'submit_survey_response'); // Backward compatibility
            })->count(),
            'consentCount' => (clone $baseQuery)->where(function($q) {
                $q->where('action', 'compliance')
                  ->where('description', 'LIKE', '%consent%')
                  ->orWhereIn('action', ['consent_given', 'consent_denied', 'consent_revoked']); // Backward compatibility
            })->count(),
            'dataAccessCount' => (clone $baseQuery)->where('action', 'data_access')->count(),
            'dataModificationCount' => (clone $baseQuery)->where('action', 'data_modification')->count(),
        ];

        // Log viewing of audit logs using AuditService
        $auditService = app(\App\Services\AuditService::class);
        $auditService->logDataAccess(
            'audit_log',
            null,
            'view_audit_logs',
            $request,
            [
                'filters' => $request->except('page'),
            ]
        );

        // Ensure admin is passed as object for view compatibility
        $adminData = is_array($admin) ? (object)$admin : $admin;
        return view('admin.audit-logs', [
            'admin' => $adminData,
            'auditLogs' => $auditLogs,
            'actions' => $actions,
            'userTypes' => $userTypes,
            'resourceTypes' => $resourceTypes,
            'action' => $action,
            'userType' => $userType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'search' => $search,
            'perPage' => $perPage,
            'stats' => $stats,
        ]);
    }

    public function aiInsights()
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Log viewing of AI insights for audit trail using AuditService
        $auditService = app(\App\Services\AuditService::class);
        $auditService->logDataAccess(
            'ai_insights',
            null,
            'view_dashboard',
            request()
        );

        // Ensure admin is passed as object for view compatibility
        $adminData = is_array($admin) ? (object)$admin : $admin;
        return view('admin.ai-insights', ['admin' => $adminData]);
    }
}
