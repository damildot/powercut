<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'whatsapp_number',
        'whatsapp_default_message_tr',
        'whatsapp_default_message_en',
        'seo_title_tr',
        'seo_title_en',
        'seo_description_tr',
        'seo_description_en',
        'address_tr',
        'address_en',
        'working_hours_tr',
        'working_hours_en',
        'map_embed_url',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
