<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\TenantService::class, function ($app) {
            return new \App\Services\TenantService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();


        // Dynamic Flasher Position (Check URL segment as fallback if locale is not yet set by middleware)
        $currentLocale = request()->segment(1);
        if (!in_array($currentLocale, ['ar', 'en'])) {
            $currentLocale = app()->getLocale();
        }

        $position = $currentLocale == 'ar' ? 'top-left' : 'top-right';

        // Update configuration for multiple adapters
        config([
            // Default flasher (notyf/native)
            'flasher.options.position' => $position,

            // SweetAlert2
            'flasher.plugins.sweetalert.position' => $currentLocale == 'ar' ? 'top-start' : 'top-end',

            // Toastr (if used)
            'flasher.plugins.toastr.positionClass' => 'toast-' . $position,
        ]);

        Gate::before(function ($user, $ability) {
            // 1. Super Admin bypass
            if ($user->id === 1 || $user->role_id === 1) {
                return true;
            }

            // 2. Exact ability match (e.g. 'reports_properties', 'stores_read')
            if ($user->hasAbility($ability)) {
                return true;
            }

            // 3. Dynamic check for module base gates (e.g. 'reports', 'stores')
            $role = $user->role;
            if ($role && $role->permissions) {
                $hasAnyModulePermission = $role->permissions->contains(function ($perm) use ($ability) {
                    return str_starts_with($perm->name, $ability . '_');
                });

                if ($hasAnyModulePermission) {
                    return true;
                }
            }

            return null; // Fallback to other gates/policies if needed
        });

        \Illuminate\Http\Request::macro('hasValidSignature', function ($absolute = true) {
            if ('livewire/upload-file' || 'livewire/preview-file' == request()->path()) {
                return true;
            }
            return \Illuminate\Support\Facades\URL::hasValidSignature($this, $absolute);
        });
    }
}
