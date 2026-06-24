<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Dashboard\Filterable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Dashboard\HasCreatedBy;
use App\Traits\Dashboard\CanBeDeleted;

class PaymentEntity extends Model
{
    use HasFactory, Filterable, HasTranslations, SoftDeletes, HasCreatedBy, CanBeDeleted;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'type',
        'status',
        'created_by'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $restrictiveRelations = [
        'storeBankAccounts' => 'payment_entities.delete_restriction'
    ];

    public function storeBankAccounts()
    {
        return $this->hasMany(StoreBankAccount::class, 'payment_entity_id');
    }
}
