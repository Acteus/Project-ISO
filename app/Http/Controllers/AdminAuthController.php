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
        $admin->auditLogs()->create([
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
        $request->user()->auditLogs()->create([
            'action' => 'logout',
            'description' => 'Admin logged out',
            'ip_address' => $request->ip(),
        ]);

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'admin' => $request->user()
        ]);
    }
}
