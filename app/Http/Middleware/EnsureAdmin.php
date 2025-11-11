<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure the user is authenticated as an admin.
 * 
 * This middleware checks for admin authentication via session (for web routes).
 * It validates that the admin in session still exists and is valid.
 * It provides a unified approach to admin authorization across the application.
 */
class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionAdmin = session('admin');

        if (!$sessionAdmin) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);

            // Redirect to login with error message
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Unauthorized. Admin access required.',
                    'redirect' => route('student.login')
                ], 403);
            }

            return redirect()->route('student.login')
                ->with('error', 'You must be logged in as an admin to access this page.');
        }

        // SECURITY FIX: Validate that the admin in session still exists and is valid
        // This prevents session fixation and ensures admin hasn't been deleted
        $adminId = is_object($sessionAdmin) ? $sessionAdmin->id : ($sessionAdmin['id'] ?? null);
        
        if (!$adminId) {
            // Invalid admin data in session, clear it
            session()->forget('admin');
            Log::warning('Invalid admin session data detected', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Session expired. Please login again.',
                    'redirect' => route('student.login')
                ], 403);
            }
            
            return redirect()->route('student.login')
                ->with('error', 'Session expired. Please login again.');
        }

        // Verify admin still exists in database and fetch fresh data
        $admin = Admin::find($adminId);
        
        if (!$admin) {
            // Admin was deleted, clear session
            session()->forget('admin');
            Log::warning('Admin account not found in database', [
                'admin_id' => $adminId,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Admin account not found. Please login again.',
                    'redirect' => route('student.login')
                ], 403);
            }
            
            return redirect()->route('student.login')
                ->with('error', 'Admin account not found. Please login again.');
        }

        // Refresh admin data in session to ensure it's up-to-date
        // Only store minimal required data to reduce session size
        session(['admin' => [
            'id' => $admin->id,
            'name' => $admin->name,
            'username' => $admin->username,
            'email' => $admin->email,
        ]]);

        // Add fresh admin model to request for easier access in controllers
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}



