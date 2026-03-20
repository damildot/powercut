<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('view_global_categories'));
    }

    protected $fillable = [
        'parent_id',
        'image',
        'is_active',
        'show_on_home',
        'sort_order',

        'name_tr',
        'slug_tr',
        'subtitle_tr',
        'description_tr',
        'seo_title_tr',
        'seo_description_tr',

        'name_en',
        'slug_en',
        'subtitle_en',
        'description_en',
        'seo_title_en',
        'seo_description_en',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }
}
