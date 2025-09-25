<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and has an 'active' status
        if (Auth::check() && Auth::user()->active == 0) {
            // Log out the user
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate CSRF token for security
            $request->session()->regenerateToken();

            // Redirect to login page or any specific route with a message
            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
