<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    /**
     * Determine if the user can view the roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility('roles_read');
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility('roles_create');
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        // 1. Check basic permission
        if (!$user->hasAbility('roles_update')) {
            return false;
        }

        // 2. Check editability (System protection logic)
        return $role->isEditableBy($user);
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // 1. Check basic permission
        if (!$user->hasAbility('roles_delete')) {
            return false;
        }

        // 2. Check editability (System protection logic)
        return $role->isEditableBy($user);
    }
}
