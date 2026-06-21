<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLockScreen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('web')->check() && session('is_locked') == true) {
            if (!$request->is('*/lock-screen') && !$request->routeIs('dashboard.logout') && !$request->routeIs('dashboard.unlock.screen') && !$request->routeIs('dashboard.keep.alive')) {
                return redirect()->guest(route('dashboard.lock.screen'));
            }
        }

        return $next($request);
    }
}
