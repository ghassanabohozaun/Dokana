<?php

namespace App\Traits;

use App\Scopes\StoreScope;
use App\Services\TenantService;
use App\Models\Store;
use Exception;

trait BelongsToStore
{
    /**
     * Boot the BelongsToStore trait for a model.
     *
     * @return void
     */
    protected static function bootBelongsToStore()
    {
        // 1. Apply the isolation scope automatically
        static::addGlobalScope(new StoreScope);

        // 2. Automatically inject store_id on creation
        static::creating(function ($model) {
            $tenantService = app(TenantService::class);

            // If it's a super admin, we don't auto-inject. They must specify it or it stays null (global).
            if ($tenantService->isSuperAdmin()) {
                return;
            }

            // For regular users, inject their store ID if not provided
            if (empty($model->store_id) && $tenantService->hasTenant()) {
                $model->store_id = $tenantService->getTenantId();
            } else {
                // To ensure bulletproof multi-tenancy, if a store_id is required 
                // but no tenant is active, we should throw an exception (unless it's explicitly allowed).
                // If a model is created by super admin and store_id is manually provided, use that.
                if (empty($model->store_id) && !app()->runningInConsole() && !\App::environment('testing')) {
                   // Uncomment this for strict mode if you want to force error when tenant is missing:
                   // throw new Exception('Cannot create a tenant-aware record without an active store context.');
                }
            }
        });
    }

    /**
     * Define the relationship to the store.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
