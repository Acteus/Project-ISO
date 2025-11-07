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
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'year' => 'required|in:11,12',
            'section' => 'required|string|max:10',
            'studentid' => 'required|string|unique:users,student_id',
            'password' => 'required|string|min:8|confirmed',
            'acknowledge' => 'required|accepted',
        ], [
            'email.unique' => 'This email is already registered.',
            'studentid.unique' => 'This student ID is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
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

        // Log registration for audit trail
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'student_registration',
            'description' => 'Student registered for ISO 21001 survey system',
            'ip_address' => $request->ip(),
            'new_values' => [
                'student_id' => $user->student_id,
                'name' => $user->name,
                'year_level' => $user->year_level,
                'section' => $user->section,
            ],
        ]);

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

        // Check if this is an admin login (student_id = 'admin')
        if ($request->student_id === 'admin') {
            $admin = \App\Models\Admin::where('username', $request->student_id)->first();

            if ($admin && \Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
                // Store admin in session for web authentication
                session(['admin' => $admin]);

                // Mark that we should regenerate on next request
                $request->session()->put('_should_regenerate', true);

                // Log admin login for audit trail
                AuditLog::create([
                    'admin_id' => $admin->id,
                    'action' => 'admin_login',
                    'description' => 'Admin logged into ISO 21001 survey system',
                    'ip_address' => $request->ip(),
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

            return response()->json([
                'message' => 'Invalid admin credentials. Please check your username and password.'
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

            // Log login for audit trail
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'student_login',
                'description' => 'Student logged into ISO 21001 survey system',
                'ip_address' => $request->ip(),
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

        return response()->json([
            'message' => 'Invalid credentials. Please check your Student ID and password.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $admin = session('admin');

        // Log logout for audit trail for students
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'student_logout',
                'description' => 'Student logged out of ISO 21001 survey system',
                'ip_address' => $request->ip(),
            ]);
        }

        // Log logout for audit trail for admins
        if ($admin) {
            AuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'admin_logout',
                'description' => 'Admin logged out of ISO 21001 survey system',
                'ip_address' => $request->ip(),
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

        return view('student.dashboard', compact('user'));
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
        // For now, we'll use session to store admin info since we're not using Sanctum for web routes
        // In a production app, you'd want proper session-based admin authentication
        $admin = session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Cache dashboard data for 3 minutes
        $cacheKey = 'dashboard:admin:' . $admin->id;
        $dashboardData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 180, function () {
            return [
                'totalResponses' => \App\Models\SurveyResponse::count(),
                'recentResponses' => \App\Models\SurveyResponse::latest()->take(5)->get(),
                'responsesByTrack' => \App\Models\SurveyResponse::selectRaw('track, COUNT(*) as count')
                    ->groupBy('track')
                    ->get(),
            ];
        });

        return view('admin.dashboard', array_merge(
            ['admin' => $admin],
            $dashboardData
        ));
    }

    public function viewResponse($id)
    {
        $admin = session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Get the survey response
        $response = \App\Models\SurveyResponse::findOrFail($id);

        // Log viewing of response for audit trail
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_survey_response',
            'description' => 'Admin viewed detailed survey response',
            'ip_address' => request()->ip(),
            'new_values' => ['response_id' => $response->id],
        ]);

        return view('admin.response-detail', compact('admin', 'response'));
    }

    public function allResponses(Request $request)
    {
        $admin = session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Get all survey responses with pagination
        $responses = \App\Models\SurveyResponse::latest()->paginate(15);

        // Log viewing of all responses for audit trail
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_all_responses',
            'description' => 'Admin viewed all survey responses list',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.all-responses', compact('admin', 'responses'));
    }

    public function auditLogs(Request $request)
    {
        $admin = session('admin');

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

        // Build query
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if ($action && $action !== 'all') {
            $query->where('action', $action);
        }

        if ($userType && $userType !== 'all') {
            if ($userType === 'student') {
                $query->whereNotNull('user_id');
            } elseif ($userType === 'admin') {
                $query->whereNotNull('admin_id');
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
                  ->orWhere('user_id', 'LIKE', "%{$search}%");
            });
        }

        // Get paginated results
        $auditLogs = $query->paginate($perPage)->appends($request->except('page'));

        // Get unique actions for filter dropdown
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        // Create user types manually since we derive them from user_id/admin_id
        $userTypes = collect(['student', 'admin']);

        // Get statistics for filtered results
        $stats = [
            'total' => $query->count(),
            'loginCount' => AuditLog::whereIn('action', ['student_login', 'admin_login'])->count(),
            'logoutCount' => AuditLog::whereIn('action', ['student_logout', 'admin_logout'])->count(),
            'submissionCount' => AuditLog::where('action', 'submit_survey_response')->count(),
        ];

        // Log viewing of audit logs
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_audit_logs',
            'description' => 'Admin viewed system audit logs',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.audit-logs', compact('admin', 'auditLogs', 'actions', 'userTypes', 'action', 'userType', 'dateFrom', 'dateTo', 'search', 'perPage', 'stats'));
    }

    public function aiInsights()
    {
        $admin = session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Log viewing of AI insights for audit trail
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_ai_insights',
            'description' => 'Admin accessed AI insights dashboard',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.ai-insights', compact('admin'));
    }
}
