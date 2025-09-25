<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateSessionActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle($request, Closure $next)
{
    // تحديث وقت آخر نشاط في الجلسة
    session(['last_activity_time' => now()]);

    return $next($request);
}

}
