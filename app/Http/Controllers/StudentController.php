<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
            'year' => 'required|in:11,12',
            'section' => 'required|string|max:10',
            'studentid' => 'required|string|unique:users,student_id',
            'password' => 'required|string|min:6|confirmed',
            'acknowledge' => 'required|accepted',
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
            'email' => $request->studentid . '@student.jru.edu', // Generate student email
            'password' => Hash::make($request->password), // Use provided password
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

        return response()->json([
            'message' => 'Registration successful! Welcome to the ISO 21001 Survey System.',
            'redirect' => route('survey.landing'),
            'user' => [
                'name' => $user->name,
                'student_id' => $user->student_id,
                'year_level' => $user->year_level,
                'section' => $user->section,
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

        // Regular student login
        $credentials = [
            'student_id' => $request->student_id,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Log login for audit trail
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'student_login',
                'description' => 'Student logged into ISO 21001 survey system',
                'ip_address' => $request->ip(),
            ]);

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

        // If it's an AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        // Otherwise, show the beautiful logout page
        return view('logout');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        return view('student.dashboard', compact('user'));
    }

    public function adminDashboard()
    {
        // For now, we'll use session to store admin info since we're not using Sanctum for web routes
        // In a production app, you'd want proper session-based admin authentication
        $admin = session('admin');

        if (!$admin) {
            return redirect()->route('student.login');
        }

        // Get survey analytics data
        $totalResponses = \App\Models\SurveyResponse::count();
        $recentResponses = \App\Models\SurveyResponse::latest()->take(5)->get();
        $responsesByTrack = \App\Models\SurveyResponse::selectRaw('track, COUNT(*) as count')
            ->groupBy('track')
            ->get();

        return view('admin.dashboard', compact('admin', 'totalResponses', 'recentResponses', 'responsesByTrack'));
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
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if ($action) {
            $query->where('action', $action);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Get paginated results
        $auditLogs = $query->paginate(20);

        // Get unique actions for filter dropdown
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        // Log viewing of audit logs
        AuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'view_audit_logs',
            'description' => 'Admin viewed system audit logs',
            'ip_address' => request()->ip(),
        ]);

        return view('admin.audit-logs', compact('admin', 'auditLogs', 'actions', 'action', 'dateFrom', 'dateTo'));
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
