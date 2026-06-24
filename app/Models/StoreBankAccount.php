<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\Filterable;
use App\Contracts\MustBelongToStore;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Dashboard\HasCreatedBy;

use App\Traits\Dashboard\CanBeDeleted;

class StoreBankAccount extends Model implements MustBelongToStore
{
    use HasFactory, BelongsToStore, Filterable, HasTranslations, SoftDeletes, HasCreatedBy, CanBeDeleted;

    public $translatable = ['account_holder_name'];

    protected $restrictiveRelations = [
        'transactions' => 'bank_accounts.bank_account_has_transactions',
        'withdrawals' => 'bank_accounts.bank_account_has_withdrawals',
    ];

    protected $fillable = [
        'store_id',
        'account_type',
        'payment_entity_id',
        'current_balance',
        'account_number',
        'account_holder_name',
        'iban',
        'is_default',
        'created_by'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'current_balance' => 'decimal:2',
    ];

    /**
     * Ensure only one default bank account per store.
     */
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->is_default) {
                // Unset other default accounts for the same store
                static::where('store_id', $model->store_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get the formatted IBAN with spaces every 4 characters.
     */
    public function getFormattedIbanAttribute()
    {
        if (!$this->iban) return null;
        return trim(chunk_split(str_replace(' ', '', $this->iban), 4, ' '));
    }

    // Removed cheques relationship

    public function paymentEntity()
    {
        return $this->belongsTo(PaymentEntity::class);
    }

    public function transactions()
    {
        return $this->hasMany(StoreTransaction::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(StoreWithdrawal::class);
    }
}
