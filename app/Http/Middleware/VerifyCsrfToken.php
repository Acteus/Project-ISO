<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // Log CSRF token information for debugging
        if (config('app.debug')) {
            Log::debug('CSRF Token Verification', [
                'session_token' => $request->session()->token(),
                'header_token' => $request->header('X-CSRF-TOKEN'),
                'input_token' => $request->input('_token'),
                'session_id' => $request->session()->getId(),
                'session_driver' => config('session.driver'),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }

        return parent::handle($request, $next);
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);

        // If token is missing, log it
        if (! $token) {
            Log::warning('CSRF token missing from request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => $request->session()->getId(),
            ]);
        }

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }
}
