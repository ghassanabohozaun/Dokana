<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;
use App\Traits\Dashboard\CanBeDeleted;

class StoreSupplierInvoice extends Model implements MustBelongToStore
{
    use HasFactory, SoftDeletes, BelongsToStore, Filterable, CanBeDeleted;

    protected $restrictiveRelations = [
        'payments' => 'store_supplier_invoices.invoice_has_payments',
    ];

    protected $fillable = [
        'store_id',
        'store_supplier_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'invoice_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'paid_amount' => 'integer',
        'remaining_amount' => 'integer',
        'invoice_date' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(StoreSupplier::class, 'store_supplier_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StoreSupplierPayment::class);
    }
}
