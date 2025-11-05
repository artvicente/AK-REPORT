<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // ... (constructor)

    // *** Tiyakin na nasa file mo ang method na ito ***
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 1) { // Admin
            // I-check ang tamang path/constant
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        // Client (role=2)
        return redirect()->intended(RouteServiceProvider::CLIENT_HOME);
    }
}
