<?php
namespace App\Services\Website\Auth;

use App\Repositories\Website\Auth\AuthRepository;

class AuthService
{
    protected $authRepository;

    // __construct
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    // login
    public function login($credentials, $remember, $guard)
    {
        $checkLogin = $this->authRepository->login($credentials, $remember, $guard);
        if (!$checkLogin) {
            return false;
        }
        return true;
    }

    // logout
    public function logout($guard)
    {
        return $this->authRepository->logout($guard);
    }
}
