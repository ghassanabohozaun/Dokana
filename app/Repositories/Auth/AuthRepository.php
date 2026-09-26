<?php
namespace App\Repositories\Auth;

use Illuminate\Support\Facades\Auth;

class AuthRepository
{

    // login
    public function login($credinatioals, $remmber, $gaurd)
    {
        $tenantService = app(\App\Services\TenantService::class);
        $previousTenant = $tenantService->getTenantId();
        $previousSuperAdmin = $tenantService->isSuperAdmin();

        // Temporarily clear tenant context so User lookup during attempt is not restricted
        $tenantService->setTenant(null);

        try {
            $loggedIn = Auth::guard($gaurd)->attempt($credinatioals, $remmber);

            if ($loggedIn) {
                $user = Auth::guard($gaurd)->user();
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

            // Restore if login failed
            $tenantService->setTenant($previousTenant);
            $tenantService->setSuperAdmin($previousSuperAdmin);
            return false;
        } catch (\Throwable $e) {
            $tenantService->setTenant($previousTenant);
            $tenantService->setSuperAdmin($previousSuperAdmin);
            throw $e;
        }
    }

    public function logout($gaurd){
        return Auth::guard($gaurd)->logout();
    }
}
