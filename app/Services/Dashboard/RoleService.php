<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\RoleRepository;
use App\Exceptions\DeleteRestrictionException;

class RoleService
{
    /**
     * Create a new class instance.
     */
    protected $roleRepositoy;
    // __construct
    public function __construct(RoleRepository $roleRepositoy)
    {
        $this->roleRepositoy = $roleRepositoy;
    }

    // get role
    public function getRole($id)
    {
        $role = $this->roleRepositoy->getRole($id);
        if (!$role) {
            return false;
        }
        return $role;
    }

    //get roles
    public function getRoles($request)
    {
        return $this->roleRepositoy->getRoles($request);
    }

    // get all roles for dropdowns (no filter)
    public function getAllRoles()
    {
        return $this->roleRepositoy->getAllRoles();
    }

    // store role
    public function storeRole($request)
    {
        $role = $this->roleRepositoy->storeRole($request);
        if (!$role) {
            return false;
        }
        return $role;
    }

    // role update
    public function updateRole($request, $id)
    {
        $role = $this->roleRepositoy->getRole($id);
        if (!$role) {
            return false;
        }

        $roleUpdate = $this->roleRepositoy->updateRole($request, $role);

        if (!$roleUpdate) {
            return false;
        }
        return $roleUpdate;
    }

    // delete role
    public function destroyRole($id)
    {
        // get role
        $role = $this->roleRepositoy->getRole($id);

        if (!$role) {
            throw new \Exception(__('general.no_record_found'));
        }

        // Protect System/Global Roles from deletion
        if ($role->isSystemRole()) {
            throw new DeleteRestrictionException(__('roles.cannot_delete_system_role'));
        }

        // check if any admins has role
        $role->checkRestrictiveRelations();

        // destroy role
        $roleDestroy = $this->roleRepositoy->destroyRole($role);
        if (!$roleDestroy) {
            return false;
        }

        return $roleDestroy;
    }
}
