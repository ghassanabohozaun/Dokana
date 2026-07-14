<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Dashboard\Filterable;
use App\Traits\Dashboard\HasCreatedBy;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Observers\StoreWithdrawalObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(StoreWithdrawalObserver::class)]
class StoreWithdrawal extends Model implements MustBelongToStore
{
    use HasFactory, Filterable, HasCreatedBy, BelongsToStore, SoftDeletes;

    protected $fillable = [
        'store_id',
        'store_bank_account_id',
        'amount',
        'reason',
        'withdrawal_date',
        'created_by',
    ];

    protected $casts = [
        'withdrawal_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function setWithdrawalDateAttribute($value)
    {
        if (strlen($value) === 10) {
            $this->attributes['withdrawal_date'] = $value . ' ' . now()->format('H:i:s');
        } else {
            $this->attributes['withdrawal_date'] = $value;
        }
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function paymentEntity(): BelongsTo
    {
        return $this->belongsTo(PaymentEntity::class, 'payment_entity_id');
    }

    public function supplierPayment(): BelongsTo
    {
        return $this->belongsTo(StoreSupplierPayment::class, 'store_supplier_payment_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(StoreBankAccount::class, 'store_bank_account_id');
    }
}
