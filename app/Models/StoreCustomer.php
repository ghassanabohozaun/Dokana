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

    protected $fillable = ['store_id', 'name', 'phone', 'balance', 'status'];

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeInactive($query)
    {
        return $query->whereStatus(0);
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
