<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            // I-check ang role at mag-redirect
            if (Auth::user()->role === 1) { // 1: Admin
                return redirect(RouteServiceProvider::ADMIN_HOME);
            } else { // 2: Client (o default)
                return redirect(RouteServiceProvider::CLIENT_HOME);
            }
        }
    }

    return $next($request);
}
}
