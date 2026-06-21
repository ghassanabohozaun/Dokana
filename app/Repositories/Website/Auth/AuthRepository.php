<?php
namespace App\Repositories\Website\Auth;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    // login
    public function login($credentials, $remember, $guard)
    {
        return Auth::guard($guard)->attempt($credentials, $remember);
    }

    // logout
    public function logout($guard)
    {
        return Auth::guard($guard)->logout();
    }
}
