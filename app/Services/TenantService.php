<?php

namespace App\Services;

class TenantService
{
    protected ?int $currentStoreId = null;

    protected bool $isSuperAdmin = false;

    /**
     * Set the current tenant (store) ID.
     *
     * @param int|null $storeId
     * @return void
     */
    public function setTenant(?int $storeId): void
    {
        $this->currentStoreId = $storeId;
    }

    /**
     * Get the current tenant (store) ID.
     *
     * @return int|null
     */
    public function getTenantId(): ?int
    {
        return $this->currentStoreId;
    }

    /**
     * Check if a tenant is currently set.
     *
     * @return bool
     */
    public function hasTenant(): bool
    {
        return $this->currentStoreId !== null;
    }

    /**
     * Set the super admin status.
     *
     * @param bool $status
     * @return void
     */
    public function setSuperAdmin(bool $status): void
    {
        $this->isSuperAdmin = $status;
    }

    /**
     * Check if the current context is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }
}
