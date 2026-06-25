<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\StoreSupplierPaymentObserver;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;

#[ObservedBy(StoreSupplierPaymentObserver::class)]
class StoreSupplierPayment extends Model implements MustBelongToStore
{
    use HasFactory, SoftDeletes, BelongsToStore, Filterable;

    protected $fillable = [
        'store_id',
        'store_supplier_id',
        'store_supplier_invoice_id',
        'store_bank_account_id',
        'amount',
        'payment_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'integer',
        'payment_date' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(StoreSupplier::class, 'store_supplier_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(StoreSupplierInvoice::class, 'store_supplier_invoice_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(StoreBankAccount::class, 'store_bank_account_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function withdrawal(): HasOne
    {
        return $this->hasOne(StoreWithdrawal::class);
    }
}
