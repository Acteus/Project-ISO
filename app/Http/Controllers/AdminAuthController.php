<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Log the login activity
        // Note: user_id references users table, so we set it to null for admin actions
        // or create a User record if needed for audit trail
        \App\Models\AuditLog::create([
            'user_id' => null, // Admin actions don't have a user_id since foreign key references users table
            'action' => 'login',
            'description' => 'Admin logged in',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Login successful',
            'admin' => $admin,
            'token' => $admin->createToken('admin-token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        // Log the logout activity
        // Note: user_id references users table, so we set it to null for admin actions
        \App\Models\AuditLog::create([
            'user_id' => null, // Admin actions don't have a user_id since foreign key references users table
            'action' => 'logout',
            'description' => 'Admin logged out',
            'ip_address' => $request->ip(),
        ]);

        $request->user()->currentAccessToken()->delete();

        // Check if it's an AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Logged out successfully',
                'redirect' => route('student.login')
            ]);
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        // Sanctum authenticates based on the token's tokenable_type
        // If the token was created by an Admin, $request->user() should return the Admin
        $user = $request->user();
        
        // Check if the authenticated user is an Admin
        if ($user instanceof Admin) {
            return response()->json([
                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }
        
        // If user is null, the token is invalid or not authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }
        
        // If it's a User model, try to find the corresponding Admin by email
        $admin = Admin::where('email', $user->email)->first();
        if ($admin) {
            return response()->json([
                'admin' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ]
            ]);
        }
        
        // Not an admin
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }
}
