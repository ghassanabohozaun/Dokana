<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\MustBelongToStore;
use App\Observers\StoreTransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Traits\Dashboard\HasCreatedBy;

#[ObservedBy(StoreTransactionObserver::class)]
class StoreTransaction extends Model implements MustBelongToStore
{
    use HasFactory, BelongsToStore, Filterable, HasCreatedBy;

    protected $fillable = ['store_id', 'store_customer_id', 'store_bank_account_id', 'type', 'amount', 'transaction_date', 'description', 'created_by'];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function setTransactionDateAttribute($value)
    {
        if (strlen($value) === 10) {
            $this->attributes['transaction_date'] = $value . ' ' . now()->format('H:i:s');
        } else {
            $this->attributes['transaction_date'] = $value;
        }
    }

    public function customer()
    {
        return $this->belongsTo(StoreCustomer::class, 'store_customer_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(StoreBankAccount::class, 'store_bank_account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
