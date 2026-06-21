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
        \Log::info('TenantMiddleware running', [
            'url' => $request->url(),
            'method' => $request->method(),
            'is_casher' => Auth::guard('casher')->check(),
            'session_id' => session()->getId(),
        ]);

        // Check if the user is authenticated via web or casher
        if (Auth::guard('web')->check() || Auth::guard('casher')->check()) {
            $user = Auth::guard('web')->user() ?? Auth::guard('casher')->user();
            $tenantService = app(TenantService::class);

            // 1. Handle Super Admin (id=1 or role_id=1)
            if ($user->id === 1 || $user->role_id === 1) {
                $tenantService->setSuperAdmin(true);
            }

            // 2. Set the tenant ID if available
            if ($user->store_id) {
                $tenantService->setTenant($user->store_id);
            }
        } elseif (session()->has('cashier_store_id')) {
            // Set the tenant ID if the cashier is logged in via pin
            $tenantService = app(TenantService::class);
            $tenantService->setTenant(session('cashier_store_id'));
        }

        return $next($request);
    }
}
