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

class StoreSupplier extends Model implements MustBelongToStore
{
    use HasFactory, SoftDeletes, BelongsToStore, Filterable, CanBeDeleted;

    protected $restrictiveRelations = [
        'invoices' => 'store_suppliers.supplier_has_invoices',
        'payments' => 'store_suppliers.supplier_has_payments',
    ];

    protected $fillable = [
        'store_id',
        'name',
        'mobile',
        'bank_name',
        'account_number',
        'email',
        'address',
        'status',
        'created_by',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(StoreSupplierInvoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StoreSupplierPayment::class);
    }
}
