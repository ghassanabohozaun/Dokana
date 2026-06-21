<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'symbol_ar',
        'symbol_en',
        'is_active',
    ];
}
