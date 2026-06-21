<?php
namespace App\Repositories\Auth;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{

    // login
    public function login($credinatioals, $remmber, $gaurd)
    {
        return Auth::guard($gaurd)->attempt($credinatioals, $remmber);
        //return Auth::guard($gaurd)->attempt(['email' => $credinatioals->email, 'password' => $credinatioals->password], $remmber);
    }

    public function logout($gaurd){
        return Auth::guard($gaurd)->logout();
    }
}
