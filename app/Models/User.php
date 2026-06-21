<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;
use App\Traits\Dashboard\Filterable;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\HasCreatedBy;
use App\Traits\Dashboard\CanBeDeleted;

class User extends Authenticatable implements MustBelongToStore
{
    use HasFactory, Notifiable, SoftDeletes, HasTranslations, HasApiTokens, Filterable, BelongsToStore, HasCreatedBy, CanBeDeleted;

    protected $restrictiveRelations = [];

    protected $table = 'users';

    protected $fillable = [
        'store_id',
        'role_id',
        'name',
        'email',
        'password',
        'mobile',
        'photo',
        'status',
        'created_by',
    ];

    public array $translatable = ['name'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Permission Check
    public function hasAbility($permissions)
    {
        $role = $this->role;
        if (!$role) {
            return false;
        }

        // Bypass for Super Admin
        if ($this->id === 1 || $this->role_id === 1) {
            return true;
        }

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        if (is_array($permissions)) {
            return count(array_intersect($permissions, $rolePermissions)) > 0;
        }

        return in_array($permissions, $rolePermissions);
    }

    // Formatting
    public function getCreatedAtAttribute($value)
    {
        if (request()->wantsJson()) {
            return $value;
        }
        return Carbon::parse($value)->format('d/m/Y h:i A');
    }

    public function getInitialsAttribute()
    {
        $name = $this->getTranslation('name', app()->getLocale()) ?: $this->name;
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 1));
    }

    public function userPhoto()
    {
        if ($this->photo && file_exists(public_path('uploads/users/' . $this->photo))) {
            return asset('uploads/users/' . $this->photo);
        }
        return null;
    }

    public function getAvatarColor()
    {
        $colors = ['#5A8DEE', '#FDAC41', '#FF5B5C', '#39DA8A', '#00CFDD', '#7117EA', '#272727'];
        $charIndex = abs(crc32($this->name)) % count($colors);
        return $colors[$charIndex];
    }
}
