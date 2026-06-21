<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Traits\Dashboard\Filterable;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;
use App\Traits\Dashboard\HasCreatedBy;
use App\Traits\Dashboard\CanBeDeleted;

class Department extends Model implements MustBelongToStore
{
    use SoftDeletes, HasTranslations, Filterable, BelongsToStore, HasCreatedBy, CanBeDeleted;
    
    protected $restrictiveRelations = [];
    
    protected $table = 'departments';
    protected $fillable = ['store_id', 'name', 'status', 'created_by'];

    public $timestamps = true;

    public array $translatable = ['name'];

    // scopes
    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeInactive($query)
    {
        return $query->whereStatus(0);
    }

    // relation
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
