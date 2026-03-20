<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('view_global_blog_categories'));

        static::creating(function ($category) {
            if (empty($category->slug_tr) && !empty($category->name_tr)) {
                $category->slug_tr = Str::slug($category->name_tr);
            }
            if (empty($category->slug_en) && !empty($category->name_en)) {
                $category->slug_en = Str::slug($category->name_en);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug_tr) && !empty($category->name_tr)) {
                $category->slug_tr = Str::slug($category->name_tr);
            }
            if (empty($category->slug_en) && !empty($category->name_en)) {
                $category->slug_en = Str::slug($category->name_en);
            }
        });
    }

    protected $fillable = [
        'name_tr',
        'name_en',
        'slug_tr',
        'slug_en',
        'description_tr',
        'description_en',
        'seo_title_tr',
        'seo_title_en',
        'seo_description_tr',
        'seo_description_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all blog posts in this category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'blog_category_id')
            ->orderBy('published_at', 'desc');
    }

    /**
     * Get only active posts
     */
    public function activePosts(): HasMany
    {
        return $this->posts()->where('is_active', true)->whereNotNull('published_at');
    }

    /**
     * Scope: Only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name_tr', 'asc');
    }
}

