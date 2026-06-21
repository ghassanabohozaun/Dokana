<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\MustBelongToStore;
use App\Observers\StoreTransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(StoreTransactionObserver::class)]
class StoreTransaction extends Model implements MustBelongToStore
{
    use HasFactory, BelongsToStore, Filterable;

    protected $fillable = ['store_id', 'store_customer_id', 'type', 'amount', 'transaction_date', 'description', 'created_by'];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(StoreCustomer::class, 'store_customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
