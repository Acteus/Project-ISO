<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not authenticated, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Allow access to verification-related routes even if email is not verified
        $verificationRoutes = [
            'verification.notice',
            'verification.verify',
            'verification.send',
        ];

        $routeName = $request->route()?->getName();
        if (in_array($routeName, $verificationRoutes)) {
            // Allow access to verification routes
            return $next($request);
        }

        // Check if email is verified for all other routes
        if (!$user->hasVerifiedEmail()) {
            // If it's an AJAX request, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Your email address is not verified. Please verify your email before accessing this page.',
                    'redirect' => route('verification.notice')
                ], 403);
            }

            // Redirect to verification notice page
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before accessing this page.');
        }

        return $next($request);
    }
}
