<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // I-check kung naka-login at kung ang role ay 1 (Admin)
        if (Auth::check() && Auth::user()->role === 1) {
            return $next($request);
        }

        // Kung hindi Admin, i-redirect sa home o sa login
        return redirect('/login')->with('error', 'You do not have admin access.');
    }
}
