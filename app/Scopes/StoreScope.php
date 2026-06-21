<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Services\TenantService;

class StoreScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $tenantService = app(TenantService::class);

        // Bypass for Super Admin - Check flag from TenantService to avoid recursion
        if ($tenantService->isSuperAdmin()) {
            return;
        }

        if ($tenantService->hasTenant()) {
            $builder->where(function ($query) use ($model, $tenantService) {
                $query->where($model->getTable() . '.store_id', $tenantService->getTenantId())
                      ->orWhereNull($model->getTable() . '.store_id');
            });
        }
    }
}
