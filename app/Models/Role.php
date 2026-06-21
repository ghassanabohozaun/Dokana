<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Traits\Dashboard\Filterable;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\HasCreatedBy;
use App\Traits\Dashboard\CanBeDeleted;

class Role extends Model implements MustBelongToStore
{
    use SoftDeletes, HasTranslations, HasFactory, Filterable, BelongsToStore, HasCreatedBy, CanBeDeleted;

    protected $table = 'roles';
    protected $fillable = ['store_id', 'name', 'description', 'created_by'];

    public $translatable = ['name'];

    protected $restrictiveRelations = [
        'users' => 'roles.cannot_delete_role_linked_to_users'
    ];

    // relations
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users(){
        return $this->hasMany(User::class , 'role_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Check if the role is a system (global) role.
     */
    public function isSystemRole(): bool
    {
        return $this->store_id === null;
    }

    /**
     * Check if the role can be modified by the given user.
     */
    public function isEditableBy($user): bool
    {
        // If it's a system role, only real super admins (ID 1 or Role 1) can edit it
        if ($this->isSystemRole()) {
            return $user->id === 1 || $user->role_id === 1;
        }

        // Otherwise, it's editable if it's within the same store context
        return $this->store_id === $user->store_id;
    }
}
