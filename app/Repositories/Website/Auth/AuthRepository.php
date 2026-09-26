<?php
namespace App\Repositories\Website\Auth;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    // login
    public function login($credentials, $remember, $guard)
    {
        $tenantService = app(\App\Services\TenantService::class);
        $previousTenant = $tenantService->getTenantId();
        $previousSuperAdmin = $tenantService->isSuperAdmin();

        $tenantService->setTenant(null);

        try {
            $loggedIn = Auth::guard($guard)->attempt($credentials, $remember);

            if ($loggedIn) {
                $user = Auth::guard($guard)->user();
                if ($user->id === 1 || $user->role_id === 1) {
                    $tenantService->setSuperAdmin(true);
                } else {
                    $tenantService->setSuperAdmin(false);
                }
                if ($user->store_id) {
                    $tenantService->setTenant($user->store_id);
                }
                return true;
            }

            $tenantService->setTenant($previousTenant);
            $tenantService->setSuperAdmin($previousSuperAdmin);
            return false;
        } catch (\Throwable $e) {
            $tenantService->setTenant($previousTenant);
            $tenantService->setSuperAdmin($previousSuperAdmin);
            throw $e;
        }
    }

    // logout
    public function logout($guard)
    {
        return Auth::guard($guard)->logout();
    }
}
