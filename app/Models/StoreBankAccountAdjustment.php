<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;
use App\Contracts\MustBelongToStore;
use App\Traits\Dashboard\HasCreatedBy;

class StoreBankAccountAdjustment extends Model implements MustBelongToStore
{
    use HasFactory, BelongsToStore, Filterable, HasCreatedBy;

    protected $fillable = [
        'store_id',
        'store_bank_account_id',
        'amount',
        'old_balance',
        'new_balance',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'old_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(StoreBankAccount::class, 'store_bank_account_id');
    }
}
