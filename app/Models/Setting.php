<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_title',
        'logo_light',
        'logo_dark',
        'favicon',
        'email',
        'phone',
        'whatsapp_phone',
        'address',
        'google_maps_embed',
        'facebook',
        'instagram',
        'linkedin',
        'youtube',
        'seo_title_tr',
        'seo_title_en',
        'seo_description_tr',
        'seo_description_en',
        'hero_slides',
    ];

    protected $casts = [
        'hero_slides' => 'array', // JSON verisini diziye çevirir
    ];
}
