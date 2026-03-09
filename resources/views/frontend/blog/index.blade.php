@extends('layouts.master')

@php
    use Illuminate\Support\Str;
    $loc = $locale ?? app()->getLocale();
    $isEn = $loc === 'en';

    $titleField    = $isEn ? 'title_en'    : 'title_tr';
    $excerptField  = $isEn ? 'excerpt_en'  : 'excerpt_tr';
    $contentField  = $isEn ? 'content_en'  : 'content_tr';
    $slugField     = $isEn ? 'slug_en'     : 'slug_tr';
    $imgAltField   = $isEn ? 'image_alt_en': 'image_alt_tr';
    $catNameField  = $isEn ? 'name_en'     : 'name_tr';
    $catSlugField  = $isEn ? 'slug_en'     : 'slug_tr';

    $routePrefix = $isEn ? 'blog.en.' : 'blog.';

    function blogUrl($routePrefix, $routeName, $params = []) {
        return route($routePrefix . $routeName, $params);
    }
@endphp

@section('title', $seoTitle ?? __('blog.seo_title'))
@section('meta_description', $seoDescription ?? __('blog.seo_description'))
@section('canonical', $isEn ? route('blog.en.index') : route('blog.index'))

@push('head')
    <link rel="alternate" hreflang="tr" href="{{ route('blog.index') }}">
    <link rel="alternate" hreflang="en" href="{{ route('blog.en.index') }}">
@endpush

@push('styles')
<style>
    #blog .post-item {
        border: 1px solid #f2e3cc;
        border-radius: 12px;
        transition: border-color 0.2s ease;
        background: #fff;
    }

    #blog .post-item:hover {
        border-color: #ffd08a;
        box-shadow: none;
    }

    #blog .post-meta-category a {
        background: #FFA500;
        color: #fff;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
    }

    #blog .item-link {
        color: #FFA500;
        font-weight: 600;
    }

    #blog .item-link:hover {
        color: #cc8400;
    }

    .sidebar .widget-title {
        border-left: 3px solid #FFA500;
        padding-left: 10px;
    }

    /* Arama butonu - siyah + turuncu hover, icon gradient */
    .sidebar .input-group-btn .btn-primary {
        background: #FFA500;
        border-color: #FFA500;
        color: #fff;
        transition: all 0.35s ease;
    }
    .sidebar .input-group-btn .btn-primary:hover {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        border-color: #FFA500;
        color: #FFA500;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }
    .sidebar .input-group-btn .btn-primary:hover i {
        background: linear-gradient(135deg, #FFA500 0%, #ff8c00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Etiketler - siyah + turuncu karışımı hover */
    .sidebar .tags a {
        transition: all 0.35s ease;
    }
    .sidebar .tags a:hover {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
        border-color: #FFA500 !important;
        color: #FFA500 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')
    {{-- Page Title --}}
    <section id="page-title" data-bg-parallax="{{ asset('assets/images/blog/blog-banner.png') }}">
        <div class="bg-overlay" style="opacity: 0.75;"></div>
        <div class="container">
            <div class="page-title">
                <h1 class="text-uppercase text-light">{{ __('blog.title') }}</h1>
                <span class="text-light">{{ __('blog.subtitle') }}</span>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="{{ url('/') }}" class="text-light">{{ __('blog.breadcrumb_home') }}</a></li>
                    <li class="active"><a href="#" class="text-light">{{ __('blog.breadcrumb_blog') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="shape-divider" data-style="1"></div>
    </section>

    {{-- Blog Content --}}
    <section id="page-content" class="sidebar-right">
        <div class="container">
            <div class="row">
                {{-- Main Content --}}
                <div class="content col-lg-9">
                    {{-- Category Filter Pills --}}
                    @if($categories->isNotEmpty())
                    <div class="m-b-30">
                        <a href="{{ blogUrl($routePrefix, 'index') }}"
                           class="btn btn-sm {{ !request('category') && !isset($category) && !isset($tag) ? 'btn-primary' : 'btn-light' }} m-b-5">
                            {{ __('blog.all_posts') }}
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ blogUrl($routePrefix, 'category', ['slug' => $cat->{$catSlugField}]) }}"
                           class="btn btn-sm {{ (isset($category) && $category->id === $cat->id) ? 'btn-primary' : 'btn-light' }} m-b-5">
                            {{ $cat->{$catNameField} }}
                            <span class="badge badge-sm badge-light">{{ $cat->posts_count ?? 0 }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Active Tag/Category Label --}}
                    @if(isset($tag))
                    <div class="alert alert-info m-b-20">
                        <i class="fa fa-tag"></i>
                        {{ __('blog.tags') }}: <strong>{{ $tag }}</strong>
                        <a href="{{ blogUrl($routePrefix, 'index') }}" class="float-end"><i class="icon-x"></i></a>
                    </div>
                    @endif

                    {{-- Blog Grid --}}
                    <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                        @forelse($posts as $post)
                        <div class="post-item border">
                            <div class="post-item-wrap">
                                @if($post->image)
                                <div class="post-image">
                                    <a href="{{ blogUrl($routePrefix, 'show', ['slug' => $post->{$slugField}]) }}">
                                        <img alt="{{ $post->{$imgAltField} ?? $post->{$titleField} }}"
                                             src="{{ asset('storage/' . $post->image) }}"
                                             loading="lazy">
                                    </a>
                                    @if($post->category)
                                    <span class="post-meta-category">
                                        <a href="{{ blogUrl($routePrefix, 'category', ['slug' => $post->category->{$catSlugField}]) }}">
                                            {{ $post->category->{$catNameField} }}
                                        </a>
                                    </span>
                                    @endif
                                </div>
                                @endif

                                <div class="post-item-description">
                                    <span class="post-meta-date">
                                        <i class="fa fa-calendar-o"></i>
                                        {{ $post->published_at?->format('d.m.Y') }}
                                    </span>
                                    @if($post->reading_time)
                                    <span class="post-meta-comments">
                                        <i class="fa fa-clock-o"></i>
                                        {{ $post->reading_time }} {{ __('blog.min_read') }}
                                    </span>
                                    @endif

                                    <h2>
                                        <a href="{{ blogUrl($routePrefix, 'show', ['slug' => $post->{$slugField}]) }}">
                                            {{ $post->{$titleField} }}
                                        </a>
                                    </h2>

                                    <p>{{ $post->{$excerptField} ?? Str::limit(strip_tags($post->{$contentField}), 150) }}</p>

                                    <a href="{{ blogUrl($routePrefix, 'show', ['slug' => $post->{$slugField}]) }}" class="item-link">
                                        {{ __('blog.read_more') }} <i class="icon-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-lg-12">
                            <div class="alert alert-info text-center">
                                <i class="fa fa-info-circle fa-2x m-b-10"></i>
                                <p>{{ __('blog.no_posts') }}</p>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($posts->hasPages())
                    <nav aria-label="Blog navigation">
                        {{ $posts->links() }}
                    </nav>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="sidebar sticky-sidebar col-lg-3">
                    {{-- Search Widget --}}
                    <div class="widget">
                        <h4 class="widget-title">{{ __('blog.search') }}</h4>
                        <form action="{{ blogUrl($routePrefix, 'index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('blog.search') }}"
                                       value="{{ request('search') }}">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary"><i class="icon-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>

                    {{-- Categories Widget --}}
                    @if($categories->isNotEmpty())
                    <div class="widget">
                        <h4 class="widget-title">{{ __('blog.categories') }}</h4>
                        <ul class="list list-lines">
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ blogUrl($routePrefix, 'category', ['slug' => $cat->{$catSlugField}]) }}">
                                    {{ $cat->{$catNameField} }}
                                    <span class="badge badge-sm badge-light pull-right">{{ $cat->posts_count ?? 0 }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Recent Posts Widget --}}
                    @php
                        $sidebarRecent = \App\Models\BlogPost::where('is_active', true)
                            ->whereNotNull('published_at')
                            ->where('published_at', '<=', now())
                            ->orderBy('published_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @if($sidebarRecent->isNotEmpty())
                    <div class="widget">
                        <h4 class="widget-title">{{ __('blog.recent_posts') }}</h4>
                        <div class="post-thumbnail-list">
                            @foreach($sidebarRecent as $rp)
                            <div class="post-thumbnail-entry">
                                @if($rp->image)
                                <img alt="{{ $rp->{$imgAltField} ?? $rp->{$titleField} }}"
                                     src="{{ asset('storage/' . $rp->image) }}" loading="lazy">
                                @endif
                                <div class="post-thumbnail-content">
                                    <a href="{{ blogUrl($routePrefix, 'show', ['slug' => $rp->{$slugField}]) }}">
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

                    {{-- Tags Widget --}}
                    @php
                        $tagCol = $isEn ? 'tags_en' : 'tags';
                        $sidebarTags = \App\Models\BlogPost::where('is_active', true)
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
                    @endphp
                    @if(!empty($sidebarTags))
                    <div class="widget widget-tags">
                        <h4 class="widget-title">{{ __('blog.tags') }}</h4>
                        <div class="tags">
                            @foreach($sidebarTags as $t)
                            <a href="{{ blogUrl($routePrefix, 'tag', ['tag' => $t]) }}">{{ $t }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
