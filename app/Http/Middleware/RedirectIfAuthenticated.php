<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // $guards = empty($guards) ? [null] : $guards;

        $guards = ['web', 'officess', 'companys']; // List your guards here

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'officess':
                        return redirect()->route('office/issuing'); // Define your route for the officess guard
                    case 'companys':
                        return redirect()->route('company/report/issuing'); // Define your route for the companys guard

                        case 'web':
                            return redirect()->route('report/issuing'); // Define your route for the companys guard
                   
                    default:
                        return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
