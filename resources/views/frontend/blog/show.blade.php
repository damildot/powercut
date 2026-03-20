@extends('layouts.master')

@php
    use Illuminate\Support\Str;
    $loc = $locale ?? app()->getLocale();
    $isEn = $loc === 'en';

    $titleField      = $isEn ? 'title_en'          : 'title_tr';
    $excerptField    = $isEn ? 'excerpt_en'         : 'excerpt_tr';
    $contentField    = $isEn ? 'content_en'         : 'content_tr';
    $slugField       = $isEn ? 'slug_en'            : 'slug_tr';
    $imgAltField     = $isEn ? 'image_alt_en'       : 'image_alt_tr';
    $catNameField    = $isEn ? 'name_en'            : 'name_tr';
    $catSlugField    = $isEn ? 'slug_en'            : 'slug_tr';
    $seoTitleField   = $isEn ? 'seo_title_en'       : 'seo_title_tr';
    $seoDescField    = $isEn ? 'seo_description_en' : 'seo_description_tr';

    $routePrefix = $isEn ? 'blog.en.' : 'blog.';
    $catRouteKey = $isEn ? 'category' : 'category';

    function blogShowUrl($routePrefix, $routeName, $params = []) {
        return route($routePrefix . $routeName, $params);
    }

    $pageTitle = $post->{$seoTitleField} ?? $post->{$titleField};
    $pageDesc  = $post->{$seoDescField} ?? $post->{$excerptField} ?? Str::limit(strip_tags($post->{$contentField}), 160);
    $ogImage   = $post->image ? asset('storage/' . $post->image) : asset('assets/polo/images/blog/12.jpg');
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDesc)
@php
    $postTags = $isEn ? ($post->tags_en ?? $post->tags ?? []) : ($post->tags ?? []);
@endphp
@section('meta_keywords', is_array($postTags) && !empty($postTags) ? implode(', ', $postTags) : '')
@section('canonical', $isEn ? route('blog.en.show', ['slug' => $post->slug_en]) : route('blog.show', ['slug' => $post->slug_tr]))

@push('head')
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="alternate" hreflang="tr" href="{{ route('blog.show', ['slug' => $post->slug_tr]) }}">
    <link rel="alternate" hreflang="en" href="{{ route('blog.en.show', ['slug' => $post->slug_en]) }}">

    {{-- Schema.org JSON-LD --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": "{{ $post->{$titleField} }}",
        "image": "{{ $ogImage }}",
        "datePublished": "{{ $post->published_at?->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at?->toIso8601String() }}",
        "author": {
            "@@type": "Person",
            "name": "{{ $post->author->name ?? ($settings->site_title ?? 'POWERCUT') }}"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "{{ $settings->site_title ?? 'POWERCUT' }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ $settings->logo ? asset('storage/' . $settings->logo) : asset('assets/polo/images/logo.png') }}"
            }
        },
        "description": "{{ addslashes($pageDesc) }}"
    }
    </script>
@endpush

@push('styles')
<style>
    /* Blog detay - Proje temasına uyumlu */
    #page-content.sidebar-right {
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        padding: 4rem 0;
    }
    #blog.single-post .post-meta-category a {
        color: #FFA500 !important;
        font-weight: 600;
    }
    #blog.single-post .post-meta-category a:hover {
        color: #ff8c00 !important;
    }
    .sidebar .widget-title {
        border-left: 4px solid #FFA500;
        padding-left: 12px;
        color: #1a1a1a;
        font-weight: 700;
    }
    .sidebar .list a:hover {
        color: #FFA500 !important;
    }
    .sidebar .tags a {
        transition: all 0.35s ease;
    }
    .sidebar .tags a:hover {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        border-color: #FFA500 !important;
        color: #FFA500 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    #blog.single-post .post-content h2 {
        font-size: 1.75rem !important;
        line-height: 1.35 !important;
        font-weight: 700;
        margin: 2rem 0 1rem;
        color: #1e2022;
    }
    @media (max-width: 767.98px) {
        #blog.single-post .post-content h2 {
            font-size: 1.45rem !important;
        }
    }
</style>
@endpush

@section('content')
    {{-- Page Title with Cover Image --}}
    <section id="page-title" data-bg-parallax="{{ asset('assets/images/blog/blog-banner.png') }}">
        <div class="bg-overlay" style="opacity: 0.75;"></div>
        <div class="container">
            <div class="page-title">
                <h1 class="text-uppercase text-light">{{ $post->{$titleField} }}</h1>
                <div class="breadcrumb">
                    <ul>
                        <li><a href="{{ url('/') }}" class="text-light">{{ __('blog.breadcrumb_home') }}</a></li>
                        <li><a href="{{ blogShowUrl($routePrefix, 'index') }}" class="text-light">{{ __('blog.breadcrumb_blog') }}</a></li>
                        @if($post->category)
                        <li><a href="{{ blogShowUrl($routePrefix, 'category', ['slug' => $post->category->{$catSlugField}]) }}" class="text-light">{{ $post->category->{$catNameField} }}</a></li>
                        @endif
                        <li class="active"><a href="#" class="text-light">{{ Str::limit($post->{$titleField}, 40) }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="shape-divider" data-style="1"></div>
    </section>

    {{-- Blog Post Content --}}
    <section id="page-content" class="sidebar-right">
        <div class="container">
            <div class="row">
                {{-- Main Content --}}
                <div class="content col-lg-9">
                    <div id="blog" class="single-post">
                        <div class="post-item">
                            <div class="post-item-wrap">
                                @if($post->image)
                                <div class="post-image">
                                    <x-webp-image
                                        :src="asset('storage/' . $post->image)"
                                        :alt="$post->{$imgAltField} ?? $post->{$titleField}"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                </div>
                                @endif

                                <div class="post-item-description">
                                    {{-- Post Meta --}}
                                    <div class="post-meta">
                                        <span class="post-meta-date">
                                            <i class="fa fa-calendar-o"></i>
                                            {{ $post->published_at?->format('d.m.Y') }}
                                        </span>
                                        @if($post->category)
                                        <span class="post-meta-category">
                                            <i class="fa fa-folder-o"></i>
                                            <a href="{{ blogShowUrl($routePrefix, 'category', ['slug' => $post->category->{$catSlugField}]) }}">
                                                {{ $post->category->{$catNameField} }}
                                            </a>
                                        </span>
                                        @endif
                                        @if($post->reading_time)
                                        <span class="post-meta-comments">
                                            <i class="fa fa-clock-o"></i>
                                            {{ $post->reading_time }} {{ __('blog.min_read') }}
                                        </span>
                                        @endif

                                    </div>

                                    {{-- Excerpt --}}
                                    @if($post->{$excerptField})
                                    <div class="lead m-b-20" style="font-size: 1.1rem; color: #555;">
                                        {{ $post->{$excerptField} }}
                                    </div>
                                    @endif

                                    {{-- Post Content --}}
                                    <div class="post-content">
                                        {!! $post->{$contentField} !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Post Navigation --}}
                        @if($prevPost || $nextPost)
                        <div class="post-navigation">
                            @if($prevPost)
                            <a href="{{ blogShowUrl($routePrefix, 'show', ['slug' => $prevPost->{$slugField}]) }}" class="post-prev">
                                <span>{{ __('blog.prev_post') }}</span>
                                <div class="post-prev-title">{{ $prevPost->{$titleField} }}</div>
                            </a>
                            @endif

                            <a href="{{ blogShowUrl($routePrefix, 'index') }}" class="post-all">
                                <i class="icon-grid"></i>
                            </a>

                            @if($nextPost)
                            <a href="{{ blogShowUrl($routePrefix, 'show', ['slug' => $nextPost->{$slugField}]) }}" class="post-next">
                                <span>{{ __('blog.next_post') }}</span>
                                <div class="post-next-title">{{ $nextPost->{$titleField} }}</div>
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="sidebar sticky-sidebar col-lg-3">
                    {{-- Categories Widget --}}
                    @if($categories->isNotEmpty())
                    <div class="widget">
                        <h4 class="widget-title">{{ __('blog.categories') }}</h4>
                        <ul class="list list-lines">
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ blogShowUrl($routePrefix, 'category', ['slug' => $cat->{$catSlugField}]) }}">
                                    {{ $cat->{$catNameField} }}
                                    <span class="badge badge-sm badge-light pull-right">{{ $cat->posts_count ?? 0 }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Recent Posts Widget --}}
                    @if($recentPosts->isNotEmpty())
                    <div class="widget">
                        <h4 class="widget-title">{{ __('blog.recent_posts') }}</h4>
                        <div class="post-thumbnail-list">
                            @foreach($recentPosts as $rp)
                            <div class="post-thumbnail-entry">
                                @if($rp->image)
                                <x-webp-image
                                    :src="asset('storage/' . $rp->image)"
                                    :alt="$rp->{$imgAltField} ?? $rp->{$titleField}"
                                    loading="lazy"
                                    decoding="async"
                                />
                                @endif
                                <div class="post-thumbnail-content">
                                    <a href="{{ blogShowUrl($routePrefix, 'show', ['slug' => $rp->{$slugField}]) }}">
                                        {{ $rp->{$titleField} }}
                                    </a>
                                    <span class="post-date">
                                        <i class="fa fa-clock-o"></i>
                                        {{ $rp->published_at?->format('d.m.Y') }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Tags Cloud Widget --}}
                    @if(!empty($allTags) && count($allTags) > 0)
                    <div class="widget widget-tags">
                        <h4 class="widget-title">{{ __('blog.tags') }}</h4>
                        <div class="tags">
                            @foreach($allTags as $t)
                            <a href="{{ blogShowUrl($routePrefix, 'tag', ['tag' => $t]) }}">{{ $t }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
