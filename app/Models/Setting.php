<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Contracts\MustBelongToStore;
use App\Traits\BelongsToStore;

class Setting extends Model implements MustBelongToStore
{
    use HasTranslations, BelongsToStore;
    protected $table = 'settings';
    protected $fillable = [
        'store_id',
        'site_name',
        'currency_id',
        'address',
        'description',
        'keywords',
        'phone',
        'mobile',
        'whatsapp',
        'email',
        'email_support',
        'facebook',
        'twitter',
        'instegram',
        'youtube',
        'logo',
        'favicon',
        'auth_welcome_title',
        'auth_welcome_desc',
        'auth_welcome_badge',
        'auth_welcome_footer',
    ];
    public $timestamps = false;
    public array $translatable = ['site_name', 'address', 'description', 'keywords', 'auth_welcome_title', 'auth_welcome_desc', 'auth_welcome_badge', 'auth_welcome_footer'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    // Improve the screen show
    public function getLogoUrlAttribute()
    {
        if ($this->logo && file_exists(public_path('uploads/settings/' . $this->logo))) {
            return asset('uploads/settings/' . $this->logo);
        }
        return asset('assets/dashbaord/images/logo.png');
    }

    public function getFaviconUrlAttribute()
    {
        if ($this->favicon && file_exists(public_path('uploads/settings/' . $this->favicon))) {
            return asset('uploads/settings/' . $this->favicon);
        }
        return asset('assets/dashbaord/images/favicon.png');
    }

    public function getSiteNameTranslatedAttribute()
    {
        return $this->site_name ?? 'Rental System';
    }
}
