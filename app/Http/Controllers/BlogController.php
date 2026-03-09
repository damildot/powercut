<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BlogController extends Controller
{
    private function locale(): string
    {
        return App::getLocale();
    }

    private function slugField(): string
    {
        return $this->locale() === 'en' ? 'slug_en' : 'slug_tr';
    }

    private function field(string $base): string
    {
        return $base . '_' . $this->locale();
    }

    public function index(Request $request)
    {
        $locale = $this->locale();

        $query = BlogPost::with(['category', 'author'])
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');

        if ($request->has('category')) {
            $slugCol = $locale === 'en' ? 'slug_en' : 'slug_tr';
            $query->whereHas('category', fn($q) => $q->where($slugCol, $request->category));
        }

        if ($request->has('tag')) {
            $tagCol = $locale === 'en' ? 'tags_en' : 'tags';
            $query->whereJsonContains($tagCol, $request->tag);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $titleCol = $this->field('title');
            $contentCol = $this->field('content');
            $excerptCol = $this->field('excerpt');
            $query->where(fn($q) => $q
                ->where($titleCol, 'like', "%{$s}%")
                ->orWhere($contentCol, 'like', "%{$s}%")
                ->orWhere($excerptCol, 'like', "%{$s}%")
            );
        }

        $posts = $query->paginate(9);

        $categories = BlogCategory::active()
            ->ordered()
            ->withCount('activePosts as posts_count')
            ->get();

        $seoTitle = __('blog.seo_title');
        $seoDescription = __('blog.seo_description');

        return view('frontend.blog.index', compact('posts', 'categories', 'locale', 'seoTitle', 'seoDescription'));
    }

    public function show(string $slug)
    {
        $locale = $this->locale();
        $slugCol = $this->slugField();

        $post = BlogPost::with(['category', 'author'])
            ->where($slugCol, $slug)
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $prevPost = BlogPost::where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = BlogPost::where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $recentPosts = BlogPost::where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        $categories = BlogCategory::active()
            ->ordered()
            ->withCount('activePosts as posts_count')
            ->get();

        $tagCol = $locale === 'en' ? 'tags_en' : 'tags';
        $allTags = BlogPost::where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereNotNull($tagCol)
            ->pluck($tagCol)
            ->flatten()
            ->unique()
            ->filter()
            ->take(20)
            ->values()
            ->toArray();

        return view('frontend.blog.show', compact(
            'post', 'prevPost', 'nextPost', 'recentPosts',
            'categories', 'allTags', 'locale'
        ));
    }

    public function category(string $slug)
    {
        $locale = $this->locale();
        $slugCol = $locale === 'en' ? 'slug_en' : 'slug_tr';

        $category = BlogCategory::where($slugCol, $slug)
            ->active()
            ->firstOrFail();

        $posts = BlogPost::with(['category', 'author'])
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('blog_category_id', $category->id)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::active()
            ->ordered()
            ->withCount('activePosts as posts_count')
            ->get();

        $nameCol = $this->field('name');
        $seoTitleCol = $this->field('seo_title');
        $seoDescCol = $this->field('seo_description');

        $seoTitle = $category->{$seoTitleCol} ?? ($category->{$nameCol} . ' - Blog');
        $seoDescription = $category->{$seoDescCol} ?? $category->{$this->field('description')};

        return view('frontend.blog.index', compact('posts', 'categories', 'category', 'locale', 'seoTitle', 'seoDescription'));
    }

    public function tag(string $tag)
    {
        $locale = $this->locale();

        $tagCol = $locale === 'en' ? 'tags_en' : 'tags';
        $posts = BlogPost::with(['category', 'author'])
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereJsonContains($tagCol, $tag)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        $categories = BlogCategory::active()
            ->ordered()
            ->withCount('activePosts as posts_count')
            ->get();

        $seoTitle = ucfirst($tag) . ' - Blog';
        $seoDescription = ucfirst($tag) . ' — ' . __('blog.seo_description');

        return view('frontend.blog.index', compact('posts', 'categories', 'tag', 'locale', 'seoTitle', 'seoDescription'));
    }
}
