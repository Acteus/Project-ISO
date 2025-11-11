<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\SurveyResponse;
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
            if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors and try again.');
            }
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

        // Always send verification email and redirect to verification notice page first
        // User must verify email before being asked for consent
        try {
            // Send email verification notification (now sends immediately, not queued)
            $user->sendEmailVerificationNotification();
            Log::info('Email verification sent immediately to: ' . $user->email, [
                'user_id' => $user->id,
                'email' => $user->email,
                'mail_driver' => config('mail.default'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
            // Still redirect to verification page even if email fails, user can resend
        }

        $redirect = route('verification.notice');
        $message = 'Registration successful! Please check your email to verify your account.';

        if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
            return redirect()->to($redirect)->with('success', $message);
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

            // Get the dashboard URL
            $redirectUrl = route('admin.dashboard');

            // If this is not an AJAX request, redirect directly
            if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
                return redirect()->to($redirectUrl)
                    ->with('success', 'Admin login successful! Welcome back.');
            }

            // Return JSON response for AJAX requests
            return response()->json([
                'message' => 'Admin login successful! Welcome back.',
                'redirect' => $redirectUrl,
                'user' => [
                    'name' => $admin->name,
                    'username' => $admin->username,
                    'role' => 'admin',
                ]
            ], 200, [
                'Content-Type' => 'application/json',
                'X-Redirect-URL' => $redirectUrl, // Additional header for debugging
            ]);
        }

        // If admin lookup found a user but password was wrong, return error
        // Don't reveal whether it's an admin account for security
        if ($admin) {
            // SECURITY FIX: Log failed authentication attempts
            $auditService = app(\App\Services\AuditService::class);
            $auditService->logAuthentication('login', false, $request, [
                'user_type' => 'admin',
                'admin_id' => $admin->id,
                'reason' => 'invalid_password',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
                return redirect()->back()
                    ->withInput($request->only('student_id'))
                    ->with('error', 'Invalid credentials. Please check your username and password.');
            }
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

            // ENFORCE EMAIL VERIFICATION: Check if email is verified BEFORE allowing full access
            if (!$user->hasVerifiedEmail()) {
                // Mark that we should regenerate on next request
                $request->session()->put('_should_regenerate', true);

                // Log login attempt but note it's blocked due to unverified email
                $auditService = app(\App\Services\AuditService::class);
                $auditService->logAuthentication('login', false, $request, [
                    'user_type' => 'student',
                    'user_id' => $user->id,
                    'reason' => 'email_not_verified',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Resend verification email if user is trying to login
                try {
                    $user->sendEmailVerificationNotification();
                    Log::info('Verification email resent to unverified user during login: ' . $user->email);
                } catch (\Exception $e) {
                    Log::error('Failed to resend verification email during login: ' . $e->getMessage());
                }

                // For non-AJAX requests, redirect to verification page
                // User is logged in but will be blocked from other routes by middleware
                if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
                    return redirect()->route('verification.notice')
                        ->with('error', 'Please verify your email address before accessing the system. A verification email has been sent to your inbox.');
                }

                // For AJAX requests, return JSON response
                return response()->json([
                    'message' => 'Please verify your email address before accessing the system. A verification email has been sent to your inbox.',
                    'redirect' => route('verification.notice'),
                    'email_verified' => false,
                ], 403);
            }

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

            // Determine redirect URL
            $redirectUrl = route('survey.landing');

            // If this is not an AJAX request, redirect directly (server-side redirect)
            // This handles cases where JavaScript might fail or be disabled
            if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
                return redirect()->to($redirectUrl)
                    ->with('success', 'Login successful! Welcome back.');
            }

            // For AJAX requests, return JSON with redirect URL
            return response()->json([
                'message' => 'Login successful! Welcome back.',
                'redirect' => $redirectUrl,
                'user' => [
                    'name' => $user->name,
                    'student_id' => $user->student_id,
                    'year_level' => $user->year_level,
                    'section' => $user->section,
                ]
            ], 200, [
                'Content-Type' => 'application/json',
            ]);
        }

        // SECURITY FIX: Log failed authentication attempts for students
        $auditService = app(\App\Services\AuditService::class);
        $auditService->logAuthentication('login', false, $request, [
            'user_type' => 'student',
            'login_field' => $loginField,
            'login_value' => $request->student_id, // Log the attempted login identifier
            'reason' => 'invalid_credentials',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Generic error message for security (don't reveal if username exists)
        if (!$request->ajax() && !$request->expectsJson() && !$request->wantsJson()) {
            return redirect()->back()
                ->withInput($request->only('student_id'))
                ->with('error', 'Invalid credentials. Please check your username and password.');
        }
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
            // Admin is stored as an array in session, not an object
            $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);
            if ($adminId) {
                $auditService->logAuthentication('logout', true, $request, [
                    'user_type' => 'admin',
                    'user_id' => $adminId,
                ]);
            }
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

        // Determine if the student has a previous response (SurveyResponse uses encrypted student_id)
        $hasPreviousResponse = false;
        if ($user && $user->student_id) {
            try {
                $encryptionService = app(\App\Services\EncryptionService::class);
                $encryptedStudentId = $encryptionService->encrypt($user->student_id);
                $hasPreviousResponse = \App\Models\SurveyResponse::where('student_id', $encryptedStudentId)->exists();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Unable to check previous survey response (dashboard)', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id ?? null,
                ]);
            }
        }

        return view('student.dashboard', [
            'user' => $user,
            'hasPreviousResponse' => $hasPreviousResponse,
        ]);
    }

    /**
     * Update the authenticated student's profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        // Validate inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'section' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->route('student.dashboard')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        $originalValues = [
            'name' => $user->name,
            'email' => $user->email,
            'section' => $user->section,
        ];

        // Update allowed fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->section = $request->input('section');
        $user->save();

        // Log the change for audit trail (minimal necessary info)
        $auditService = app(\App\Services\AuditService::class);
        $auditService->logDataModification(
            'user',
            $user->id,
            'update',
            [
                'name' => $originalValues['name'],
                'email' => $originalValues['email'],
                'section' => $originalValues['section'],
            ],
            [
                'name' => $user->name,
                'email' => $user->email,
                'section' => $user->section,
            ],
            $request
        );

        return redirect()->route('student.dashboard')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the authenticated student's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/', // lowercase
                'regex:/[A-Z]/', // uppercase
                'regex:/[0-9]/', // digit
                'regex:/[@$!%*#?&]/', // special char
            ],
        ], [
            'new_password.confirmed' => 'New password confirmation does not match.',
            'new_password.regex' => 'New password must contain uppercase, lowercase, number, and special character.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('student.dashboard')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->route('student.dashboard')
                ->withErrors(['current_password' => 'The current password you entered is incorrect.'])
                ->withInput()
                ->with('error', 'Unable to update password.');
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        // Log password update for audit purposes without storing sensitive data
        $auditService = app(\App\Services\AuditService::class);
        $auditService->log(
            'password_change',
            'Student updated account password',
            $request,
            [
                'resource_type' => 'user',
                'resource_id' => $user->id,
                'metadata' => [
                    'user_type' => 'student',
                    'success' => true,
                ],
            ]
        );

        // Invalidate other sessions after password change
        Auth::logoutOtherDevices($request->input('new_password'));

        return redirect()->route('student.dashboard')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Clear the authenticated student's previous survey responses
     * so they can submit another one.
     */
    public function clearResponses(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        if (!$user->student_id) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to clear responses: Student ID not found.');
        }

        try {
            // SurveyResponse stores encrypted student_id; use EncryptionService to match
            $encryptionService = app(\App\Services\EncryptionService::class);
            $encryptedStudentId = $encryptionService->encrypt($user->student_id);

            $deletedCount = SurveyResponse::where('student_id', $encryptedStudentId)->delete();

            // Log deletion for audit trail (without exposing raw student_id)
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'data_modification',
                'resource_type' => 'survey_response',
                'resource_id' => null,
                'description' => 'Student cleared their survey responses to submit another',
                'ip_address' => $request->ip(),
                'new_values' => [
                    'deleted_count' => $deletedCount,
                    'student_id' => '***REDACTED***',
                ],
            ]);

            if ($deletedCount > 0) {
                return redirect()->route('student.dashboard')
                    ->with('success', 'Your previous responses have been cleared. You can now submit a new survey.');
            }

            return redirect()->route('student.dashboard')
                ->with('info', 'No previous responses were found to clear.');
        } catch (\Exception $e) {
            Log::error('Failed to clear survey responses', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('student.dashboard')
                ->with('error', 'An error occurred while clearing responses. Please try again later.');
        }
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

        // ENFORCE EMAIL VERIFICATION: User must verify email before providing consent
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before providing consent.');
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

        // If already verified, go to consent if missing, otherwise landing
        if ($user->hasVerifiedEmail()) {
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;
            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                return redirect()->route('student.consent.required');
            }
            return redirect()->route('survey.landing');
        }

        return view('student.verify-email');
    }

    /**
     * Check verification status and redirect accordingly
     * Used by the "Continue" button on verify-email page
     */
    public function checkVerificationAndContinue(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'User not authenticated.',
                    'redirect' => route('student.login')
                ], 401);
            }
            return redirect()->route('student.login');
        }

        // Check if email is verified
        if ($user->hasVerifiedEmail()) {
            // Email is verified - check for consent
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;

            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                // Verified but no consent - redirect to consent page
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'verified' => true,
                        'hasConsent' => false,
                        'message' => 'Email verified! Redirecting to consent page...',
                        'redirect' => route('student.consent.required')
                    ]);
                }
                return redirect()->route('student.consent.required')
                    ->with('success', 'Email verified successfully! Please provide consent to continue.');
            }

            // Verified and has consent - redirect to dashboard
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'verified' => true,
                    'hasConsent' => true,
                    'message' => 'Welcome back! Redirecting to dashboard...',
                    'redirect' => route('student.dashboard')
                ]);
            }
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome back!');
        }

        // Email not verified - show error message
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'verified' => false,
                'message' => 'Your email address has not been verified yet. Please check your inbox and click the verification link in the email we sent you.',
                'redirect' => route('verification.notice')
            ], 403);
        }

        return redirect()->route('verification.notice')
            ->with('error', 'Your email address has not been verified yet. Please check your inbox and click the verification link in the email we sent you.');
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

            // Enforce consent immediately after verification
            $consentService = app(\App\Services\ConsentService::class);
            $studentId = $user->student_id ?? null;
            if ($studentId && !$consentService->hasValidConsent($studentId, 'survey_response')) {
                return redirect()->route('student.consent.required')
                    ->with('success', 'Email verified successfully! Please provide consent to continue.');
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

    /**
     * Check for dashboard updates (for smart polling)
     * Returns minimal response if no changes, full data if changes detected
     */
    public function checkDashboardUpdates(Request $request)
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $lastCheck = $request->query('last_check');

        // Get last update timestamp from cache
        $lastUpdate = \Illuminate\Support\Facades\Cache::get('dashboard:last_update', 0);

        // If no last_check provided (first poll), always return data but don't mark as "updated"
        // If last_check is provided and matches or is newer, return no changes
        if ($lastCheck && $lastCheck > 0) {
            // Convert to integer for comparison
            $lastCheckInt = (int)$lastCheck;

            // If last update is 0 (never updated) or is older/equal to last check, no changes
            if ($lastUpdate == 0 || $lastUpdate <= $lastCheckInt) {
                return response()->json([
                    'updated' => false,
                    'timestamp' => $lastUpdate ?: now()->timestamp,
                ]);
            }
        }

        // If we get here, either:
        // 1. No last_check provided (first poll) - return data but it's initial load
        // 2. lastUpdate > lastCheck (changes detected) - return updated data

        // Changes detected - fetch updated data
        // Use same queries as adminDashboard for consistency
        $dashboardData = [
            'updated' => true,
            'timestamp' => $lastUpdate ?: now()->timestamp,
            'totalResponses' => \App\Models\SurveyResponse::count(),
            'recentResponses' => \App\Models\SurveyResponse::select(['id', 'track', 'created_at'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($response) {
                    return [
                        'id' => $response->id,
                        'track' => $response->track,
                        'created_at' => $response->created_at->format('M j, Y g:i A'),
                        'created_at_timestamp' => $response->created_at->timestamp,
                    ];
                }),
            'responsesByTrack' => \App\Models\SurveyResponse::selectRaw('track, COUNT(*) as count')
                ->groupBy('track')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function ($track) {
                    return [
                        'track' => $track->track,
                        'count' => $track->count,
                    ];
                }),
            'auditEventsCount' => \App\Models\AuditLog::where('action', 'submit_survey_response')->count(),
        ];

        return response()->json($dashboardData);
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

        // Get CSP nonce from request attributes (set by SecurityHeaders middleware)
        $cspNonce = $request->attributes->get('csp-nonce', '');

        return view('admin.all-responses', [
            'admin' => $adminData,
            'responses' => $responses,
            'cspNonce' => $cspNonce,
        ]);
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
        // SECURITY FIX: Separate successful and failed login counts
        $stats = [
            'total' => $baseQuery->count(),
            'loginCount' => (clone $baseQuery)->where(function($q) {
                // Count successful logins: authentication action with success=true or legacy actions
                $q->where(function($q1) {
                    $q1->where('action', 'authentication')
                       ->where('description', 'LIKE', '%login%')
                       ->where(function($q2) {
                           // Check for successful login (success=true or success not false)
                           $q2->where('description', 'LIKE', '%successful%')
                              ->orWhere(function($q3) {
                                  // Check JSON for success=true (various formats)
                                  $q3->where('new_values', 'LIKE', '%"success":true%')
                                     ->orWhere('new_values', 'LIKE', '%"success":1%')
                                     ->orWhere(function($q4) {
                                         // If no success field, assume successful (old format)
                                         $q4->whereNull('new_values')
                                            ->orWhere(function($q5) {
                                                $q5->where('new_values', 'NOT LIKE', '%"success":false%')
                                                   ->where('new_values', 'NOT LIKE', '%"success":0%')
                                                   ->where('description', 'NOT LIKE', '%failed%');
                                            });
                                     });
                              });
                       });
                })
                ->orWhereIn('action', ['student_login', 'admin_login']); // Backward compatibility - assume successful
            })->count(),
            'failedLoginCount' => (clone $baseQuery)->where(function($q) {
                // SECURITY FIX: Count failed authentication attempts
                $q->where('action', 'authentication')
                  ->where('description', 'LIKE', '%login%')
                  ->where(function($q2) {
                      // Check for failed login indicators
                      $q2->where('description', 'LIKE', '%failed%')
                         ->orWhere('new_values', 'LIKE', '%"success":false%')
                         ->orWhere('new_values', 'LIKE', '%"success":0%');
                  });
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

    /**
     * Show analytics dashboard view
     */
    public function showAnalytics(Request $request)
    {
        // Admin is validated by EnsureAdmin middleware
        $admin = request()->get('admin') ?? session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Log viewing of analytics for audit trail using AuditService
        $adminId = is_array($admin) ? ($admin['id'] ?? null) : ($admin->id ?? null);

        if ($adminId) {
            \App\Models\AuditLog::create([
                'admin_id' => $adminId,
                'action' => 'view_analytics',
                'description' => 'Viewed analytics dashboard',
                'ip_address' => request()->ip(),
            ]);
        }

        // Get analytics data using SurveyController's logic
        // We'll use the AnalyticsService or calculate directly
        $analytics = $this->getAnalyticsData($request);

        // Check if there's any survey data
        $hasData = $analytics !== null && isset($analytics['total_responses']) && $analytics['total_responses'] > 0;

        // Always provide analytics structure to view (either real data or empty structure)
        // This ensures the view never has undefined $analytics variable
        $analyticsData = $analytics ?? $this->getEmptyAnalyticsStructure();

        // Ensure admin is passed as object for view compatibility
        $adminData = is_array($admin) ? (object)$admin : $admin;

        // Get CSP nonce from request attributes (set by SecurityHeaders middleware)
        $cspNonce = $request->attributes->get('csp-nonce', '');

        return view('analytics.index', [
            'admin' => $adminData,
            'analytics' => $analyticsData,
            'noData' => !$hasData,
            'cspNonce' => $cspNonce,
        ]);
    }

    /**
     * Get analytics data (similar to SurveyController::calculateAnalytics)
     */
    private function getAnalyticsData(Request $request)
    {
        $query = \App\Models\SurveyResponse::query();

        // Apply filters from request
        if ($request->has('track') && $request->track) {
            $query->where('track', $request->track);
        }

        if ($request->has('grade_level') && $request->grade_level) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        // Date range filtering
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $responses = $query->get();

        // Return null if no responses (will be handled by controller)
        if ($responses->isEmpty()) {
            return null;
        }

        // Calculate ISO 21001 Composite Scores
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

        $avgSatisfaction = round($responses->avg('overall_satisfaction'), 2);
        $avgGrade = $responses->avg('grade_average') ?? 0;
        $avgAttendance = $responses->avg('attendance_rate') ?? 0;

        return [
            'total_responses' => $responses->count(),
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
                'track' => $responses->groupBy('track')->map->count()->toArray() ?: [],
                'grade_level' => $responses->groupBy('grade_level')->map->count()->toArray() ?: [],
                'academic_year' => $responses->groupBy('academic_year')->map->count()->toArray() ?: [],
                'semester' => $responses->groupBy('semester')->map->count()->toArray() ?: [],
            ],
            'consent_rate' => round(($responses->where('consent_given', true)->count() / $responses->count()) * 100, 2),
        ];
    }

    /**
     * Get empty analytics structure for when there's no data
     */
    private function getEmptyAnalyticsStructure()
    {
        return [
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
        ];
    }
}
