<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Nationality extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name'];

    public array $translatable = ['name'];

    /**
     * Get the customers with this nationality.
     */
    // Removed customers relationship
}
