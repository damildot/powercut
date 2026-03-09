<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
use App\Models\ProductMedia;
use App\Models\ProductSpecification;
use App\Models\ProductDocument; 

class Product extends Model
{
    use HasFactory;

    protected $casts = [
    'specifications_tr' => 'array', // JSON verisini Array yapar
    'specifications_en' => 'array', // JSON verisini Array yapar
    'gallery' => 'array',           // Çoklu resimleri Array yapar
    'is_active' => 'boolean',       // 1/0 verisini True/False yapar
    'is_featured' => 'boolean',
    'is_new' => 'boolean',
    ];

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'thumbnail',
        'is_featured',
        'is_new',
        'is_active',
        'sort_order',
        'views_count',

        'name_tr',
        'slug_tr',
        'subtitle_tr',
        'short_description_tr',
        'description_tr',
        'seo_title_tr',
        'seo_description_tr',

        'name_en',
        'slug_en',
        'subtitle_en',
        'short_description_en',
        'description_en',
        'seo_title_en',
        'seo_description_en',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Medya: image + video
    public function media()
    {
        return $this->hasMany(ProductMedia::class)
            ->orderBy('sort_order');
    }

    public function images()
    {
        return $this->media()->where('media_type', 'image');
    }

    public function videos()
    {
        return $this->media()->where('media_type', 'video');
    }

    // Teknik özellikler
    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class)
            ->orderBy('sort_order');
    }

    // Dokümanlar
    public function documents()
    {
        return $this->hasMany(ProductDocument::class)
            ->orderBy('sort_order');
    }
}