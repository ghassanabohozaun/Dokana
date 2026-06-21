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

    public $translatable = ['bank_name', 'account_holder_name'];

    protected $restrictiveRelations = [];

    protected $fillable = [
        'store_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'iban',
        'is_default',
        'created_by'
    ];

    protected $casts = [
        'is_default' => 'boolean'
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
}
