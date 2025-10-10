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
            'password' => Hash::make($request->studentid), // Use student ID as password
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
            'redirect' => route('survey.form'),
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
                'redirect' => route('survey.form'),
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

        // Log logout for audit trail
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'student_logout',
                'description' => 'Student logged out of ISO 21001 survey system',
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return redirect()->route('student.login');
        }

        return view('student.dashboard', compact('user'));
    }
}
