<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use App\Traits\Dashboard\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Dashboard\HasCreatedBy;
use App\Traits\Dashboard\CanBeDeleted;

class Store extends Model
{
    use HasFactory, HasTranslations, Filterable, SoftDeletes, HasCreatedBy, CanBeDeleted, \App\Traits\Dashboard\HasAvatar;

    protected $table = 'stores';

    protected $restrictiveRelations = [
        'users'             => 'stores.cannot_delete_has_users',
        'bankAccounts'      => 'stores.cannot_delete_has_bank_accounts',
        'departments'       => 'stores.cannot_delete_has_departments',
        'roles'             => 'stores.cannot_delete_has_roles',
        'customers'         => 'stores.cannot_delete_has_customers',
        'transactions'      => 'stores.cannot_delete_has_transactions',
        'withdrawals'       => 'stores.cannot_delete_has_withdrawals',
    ];

    protected $fillable = [
        'name',
        'subscription_plan',
        'status',
        'address',
        'phone',
        'email',
        'logo',
        'created_by'
    ];

    public array $translatable = ['name'];
 
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(StoreBankAccount::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function customers()
    {
        return $this->hasMany(StoreCustomer::class);
    }

    public function transactions()
    {
        return $this->hasMany(StoreTransaction::class);
    }

    // Improve the screen show
    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('uploads/stores/' . $this->logo))) {
            return asset('uploads/stores/' . $this->logo);
        }
        return null;
    }

}
