<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to ensure the user is authenticated as an admin.
 * 
 * This middleware checks for admin authentication via session (for web routes).
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
        $admin = session('admin');

        if (!$admin) {
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

        // Add admin to request for easier access in controllers
        $request->merge(['admin' => $admin]);

        return $next($request);
    }
}

