<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id',
        'user_id',
        'title_tr',
        'title_en',
        'slug_tr',
        'slug_en',
        'excerpt_tr',
        'excerpt_en',
        'content_tr',
        'content_en',
        'image',
        'image_alt_tr',
        'image_alt_en',
        'tags',
        'tags_en',
        'reading_time',
        'views_count',
        'is_active',
        'is_featured',
        'sort_order',
        'published_at',
        'seo_title_tr',
        'seo_title_en',
        'seo_description_tr',
        'seo_description_en',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'tags_en' => 'array',
    ];

    /**
     * Boot method: Auto-calculate reading time
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            $content = strip_tags($post->content_tr . ' ' . $post->content_en);
            $wordCount = str_word_count($content);
            $post->reading_time = max(1, ceil($wordCount / 200));

            if (empty($post->slug_tr) && !empty($post->title_tr)) {
                $post->slug_tr = Str::slug($post->title_tr);
            }
            if (empty($post->slug_en) && !empty($post->title_en)) {
                $post->slug_en = Str::slug($post->title_en);
            }
        });
    }

    /**
     * Relationship: Blog Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Relationship: Author (User)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Only active posts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNotNull('published_at');
    }

    /**
     * Scope: Featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Published posts (not scheduled for future)
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Scope: Ordered by published date
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get formatted published date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->format('d.m.Y') ?? '';
    }

    /**
     * Get human-readable published date
     */
    public function getHumanDateAttribute(): string
    {
        return $this->published_at?->diffForHumans() ?? '';
    }
}

