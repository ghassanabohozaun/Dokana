<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Dashboard\CanBeDeleted;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;

class StoreCustomer extends Model implements MustBelongToStore
{
    use HasFactory, CanBeDeleted, BelongsToStore, Filterable;

    protected $fillable = ['store_id', 'name', 'phone', 'balance', 'status', 'bypass_debt_limit', 'max_debt_limit', 'is_walk_in'];

    protected $casts = [
        'bypass_debt_limit' => 'boolean',
        'is_walk_in' => 'boolean',
        'max_debt_limit' => 'float',
    ];

    protected $appends = ['calculated_balance'];

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeInactive($query)
    {
        return $query->whereStatus(0);
    }

    public function scopeWalkIn($query)
    {
        return $query->where('is_walk_in', true);
    }

    public function transactions()
    {
        return $this->hasMany(StoreTransaction::class)->latest();
    }

    protected $restrictiveRelations = [
        'transactions' => 'store_customers.customer_has_transactions',
    ];

    public function getCalculatedBalanceAttribute()
    {
        return ($this->total_debts ?? 0) - ($this->total_payments ?? 0);
    }

}
