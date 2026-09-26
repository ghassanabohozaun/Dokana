<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantService = app(TenantService::class);

        $isDashboard = $request->is('*/dashboard*') || $request->is('dashboard*');
        $isCasher = $request->is('*/casher*') || $request->is('casher*');

        if ($isDashboard) {
            // Dashboard strictly resolves user from 'web' guard
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->id === 1 || $user->role_id === 1) {
                    $tenantService->setSuperAdmin(true);
                }
                if ($user->store_id) {
                    $tenantService->setTenant($user->store_id);
                }
            }
        } elseif ($isCasher) {
            // Casher strictly resolves from 'casher' guard or cashier session
            if (Auth::guard('casher')->check()) {
                $user = Auth::guard('casher')->user();
                if ($user->id === 1 || $user->role_id === 1) {
                    $tenantService->setSuperAdmin(true);
                }
                if ($user->store_id) {
                    $tenantService->setTenant($user->store_id);
                }
            } elseif (session()->has('cashier_store_id')) {
                $tenantService->setTenant(session('cashier_store_id'));
            }
        } else {
            // General / other routes (fallback to web then casher)
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                if ($user->id === 1 || $user->role_id === 1) {
                    $tenantService->setSuperAdmin(true);
                }
                if ($user->store_id) {
                    $tenantService->setTenant($user->store_id);
                }
            } elseif (Auth::guard('casher')->check()) {
                $user = Auth::guard('casher')->user();
                if ($user->id === 1 || $user->role_id === 1) {
                    $tenantService->setSuperAdmin(true);
                }
                if ($user->store_id) {
                    $tenantService->setTenant($user->store_id);
                }
            } elseif (session()->has('cashier_store_id')) {
                $tenantService->setTenant(session('cashier_store_id'));
            }
        }

        return $next($request);
    }
}
