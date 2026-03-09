@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@php
    $isEn = app()->getLocale() === 'en';
    $nameField = $isEn ? 'name_en' : 'name_tr';
    $slugField = $isEn ? 'slug_en' : 'slug_tr';
    $shortDescField = $isEn ? 'short_description_en' : 'short_description_tr';
    $categoryDescField = $isEn ? 'description_en' : 'description_tr';
    $seoTitleField = $isEn ? 'seo_title_en' : 'seo_title_tr';
    $seoDescField = $isEn ? 'seo_description_en' : 'seo_description_tr';
    $digerSlug = $isEn ? 'other' : 'diger';

    $selectedCategoryName = $selectedCategory ? ($selectedCategory->{$nameField} ?? $selectedCategory->name_tr) : null;
    $pageTitle = $selectedCategoryName ?: (($showUncategorized ?? false) ? __('nav.other') : __('products.page_title'));
    $pageDesc = $settings->seo_description_tr ?? 'Metal kesim, şerit testere ve endüstriyel makina ürün kataloğu.';
    $pageKeywords = 'metal kesim, şerit testere, endüstriyel makina, ürün kataloğu';
    $pageIntro = __('products.default_intro');

    if ($showUncategorized ?? false) {
        $pageDesc = $settings->seo_description_tr ?? 'Kategorisiz ürünler.';
        $pageIntro = __('products.uncategorized_intro');
    } elseif ($selectedCategory ?? null) {
        $pageDesc = $selectedCategory->{$seoDescField}
            ?? $selectedCategory->seo_description_tr
            ?? Str::limit(strip_tags($selectedCategory->{$categoryDescField} ?? $selectedCategory->description_tr ?? ''), 160);
        $pageKeywords = ($selectedCategory->name_tr ?? $selectedCategoryName) . ', metal kesim, şerit testere';
        $pageIntro = Str::limit(strip_tags($selectedCategory->{$categoryDescField} ?? $selectedCategory->description_tr ?? ''), 180) ?: __('products.default_intro');
    }
@endphp

@section('title', $pageTitle . ' | ' . ($settings->site_title ?? 'POWERCUT'))
@section('meta_description', $pageDesc)
@section('meta_keywords', $pageKeywords)
@section('canonical', ($selectedCategory ?? null) ? route('products.index', $selectedCategory->{$slugField} ?? $selectedCategory->slug_tr) : (($showUncategorized ?? false) ? route('products.index', $digerSlug) : route('products.index')))

@push('head')
    <meta property="og:title" content="{{ $pageTitle }} | {{ $settings->site_title ?? 'POWERCUT' }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    @if($products->isNotEmpty())
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ItemList",
        "name": "{{ addslashes($pageTitle) }}",
        "description": "{{ addslashes($pageDesc) }}",
        "numberOfItems": {{ $products->total() }},
        "itemListElement": [
            @foreach($products as $index => $p)
            {
                "@@type": "ListItem",
                "position": {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }},
                "url": "{{ route('products.show', $p->slug_tr) }}",
                "name": "{{ addslashes($p->{$nameField} ?? $p->name_tr) }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endif
@endpush

@section('content')
<section id="page-title" class="products-catalog-hero">
    <div class="container">
        <div class="page-title">
            <h1 class="text-uppercase text-light">{{ $pageTitle }}</h1>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="{{ route('home') }}" class="text-light">{{ __('nav.home') }}</a></li>
                @if($selectedCategoryName || ($showUncategorized ?? false))
                <li><a href="{{ route('products.index') }}" class="text-light">{{ __('nav.products') }}</a></li>
                <li class="active"><a href="#" class="text-light">{{ $selectedCategoryName ?? __('nav.other') }}</a></li>
                @else
                <li class="active"><a href="#" class="text-light">{{ __('nav.products') }}</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="shape-divider" data-style="1"></div>
</section>

<section class="p-t-40 p-b-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <aside class="product-category-panel">
                    <div class="product-category-panel-head">
                        <h4>{{ __('products.categories') }}</h4>
                    </div>

                    <div class="product-category-accordion">
                        <div class="accordion-item {{ !($selectedCategory ?? null) && !($showUncategorized ?? false) ? 'active' : '' }}">
                            <a href="{{ route('products.index') }}" class="accordion-link d-flex align-items-center justify-content-between">
                                <span>{{ __('products.all') }}</span>
                            </a>
                        </div>

                        @foreach($categories->where('parent_id', null) as $parent)
                            @php
                                $children = $categories->where('parent_id', $parent->id);
                                $isParentSelected = $selectedCategory && ($selectedCategory->id === $parent->id || $selectedCategory->parent_id === $parent->id);
                                $hasChildren = $children->isNotEmpty();
                            @endphp

                            <div class="accordion-item {{ $isParentSelected ? 'active' : '' }}">
                                @if($hasChildren)
                                    <details class="accordion-details" {{ $isParentSelected ? 'open' : '' }}>
                                        <summary class="accordion-toggle d-flex align-items-center justify-content-between">
                                            <span>{{ $parent->{$nameField} ?? $parent->name_tr }}</span>
                                            <i class="icon-chevron-down accordion-icon"></i>
                                        </summary>
                                        <div class="accordion-body">
                                            <a href="{{ route('products.index', $parent->{$slugField} ?? $parent->slug_tr) }}" class="accordion-child-link {{ $selectedCategory && $selectedCategory->id === $parent->id ? 'is-current' : '' }}">
                                                {{ $parent->{$nameField} ?? $parent->name_tr }}
                                            </a>
                                            @foreach($children as $child)
                                                <a href="{{ route('products.index', $child->{$slugField} ?? $child->slug_tr) }}" class="accordion-child-link {{ $selectedCategory && $selectedCategory->id === $child->id ? 'is-current' : '' }}">
                                                    {{ $child->{$nameField} ?? $child->name_tr }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <a href="{{ route('products.index', $parent->{$slugField} ?? $parent->slug_tr) }}" class="accordion-link d-flex align-items-center justify-content-between">
                                        <span>{{ $parent->{$nameField} ?? $parent->name_tr }}</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach

                        @if(($uncategorizedProductsCount ?? 0) > 0)
                            <div class="accordion-item {{ ($showUncategorized ?? false) ? 'active' : '' }}">
                                <a href="{{ route('products.index', $digerSlug) }}" class="accordion-link d-flex align-items-center justify-content-between">
                                    <span>{{ __('nav.other') }}</span>
                                    <span class="category-count">{{ $uncategorizedProductsCount }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>

            <div class="col-lg-9">
                @if($products->isNotEmpty())
                    <div class="products-grid-head">
                        <h2>{{ $pageTitle }}</h2>
                    </div>

                    <div class="row">
                        @foreach($products as $product)
                            @php
                                $productName = $product->{$nameField} ?? $product->name_tr;
                                $imageItems = $product->media ? $product->media->where('media_type', 'image') : collect();
                                $productImages = $imageItems->isNotEmpty()
                                    ? $imageItems->map(fn($m) => $m->path ? asset('storage/' . $m->path) : null)->filter()->values()
                                    : collect();
                                if ($productImages->isEmpty() && $product->thumbnail) {
                                    $productImages = collect([asset('storage/' . $product->thumbnail)]);
                                }
                                $hasSecondImage = $productImages->count() > 1;
                                $productImage = $productImages->first();
                                $productShortDesc = $product->{$shortDescField} ?? $product->short_description_tr;
                                if (!$productShortDesc) {
                                    $descRaw = ($isEn ? $product->description_en : $product->description_tr) ?? '';
                                    $productShortDesc = $descRaw ? Str::limit(strip_tags($descRaw), 70) : __('products.description_fallback');
                                }
                            @endphp

                            <div class="col-md-6 col-xl-4 m-b-24">
                                <article class="product-card">
                                    <a href="{{ route('products.show', $product->slug_tr) }}" class="product-card-media">
                                        @if($productImage)
                                            <img src="{{ $productImage }}" alt="{{ $productName }}" loading="lazy" class="product-card-image product-card-image-main">
                                            @if($hasSecondImage)
                                            <img src="{{ $productImages->get(1) }}" alt="{{ $productName }}" loading="lazy" class="product-card-image product-card-image-hover">
                                            @endif
                                        @else
                                            <div class="product-card-placeholder">
                                                <i class="icon-camera" aria-hidden="true"></i>
                                                <span>{{ __('products.image_placeholder') }}</span>
                                            </div>
                                        @endif
                                        <span class="product-card-overlay">{{ __('products.view_detail') }} <i class="icon-chevron-right"></i></span>
                                    </a>
                                    <div class="product-card-body">
                                        @if($product->brand)
                                        <span class="product-card-brand">{{ $product->brand->name }}</span>
                                        @endif
                                        <h3 class="product-card-title">
                                            <a href="{{ route('products.show', $product->slug_tr) }}">{{ $productName }}</a>
                                        </h3>
                                        <a href="{{ route('products.show', $product->slug_tr) }}" class="product-card-link">
                                            {{ __('products.view_detail') }} <i class="icon-chevron-right"></i>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center m-t-20">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="products-empty-state text-center">
                        <span class="products-empty-icon"><i class="icon-layers"></i></span>
                        <h3>{{ __('products.empty_title') }}</h3>
                        <p>{{ __('products.empty_desc') }}</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('products.go_all') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    :root {
        --home-accent: #27445d;
        --home-accent-soft: rgba(39,68,93,0.08);
        --home-accent-border: rgba(39,68,93,0.18);
    }
    .products-catalog-hero {
        background:
            linear-gradient(120deg, rgba(13,27,42,0.97) 0%, rgba(21,36,52,0.94) 48%, rgba(39,68,93,0.28) 100%),
            url('{{ asset("assets/images/products-banner.png") }}') center/cover no-repeat;
        color: #fff;
        padding: 72px 0 58px;
        position: relative;
    }
    .products-catalog-hero .page-title {
        text-align: center;
        padding: 0;
    }
    .product-category-panel {
        background: #fff;
        border: 1px solid #e7ecf1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 32px rgba(15,23,42,0.05);
        position: sticky;
        top: 24px;
    }
    .product-category-panel-head {
        padding: 24px 24px 18px;
        border-bottom: 1px solid #edf1f5;
    }
    .product-category-panel-head h4 {
        margin-bottom: 6px;
    }
    .product-category-panel-head p {
        margin-bottom: 0;
        color: #667085;
        font-size: 14px;
        line-height: 1.6;
    }
    .product-category-accordion .accordion-item + .accordion-item {
        border-top: 1px solid #edf1f5;
    }
    .product-category-accordion .accordion-link,
    .product-category-accordion .accordion-toggle {
        width: 100%;
        padding: 16px 20px;
        color: #1f2937;
        text-decoration: none;
        background: transparent;
        border: 0;
        cursor: pointer;
        font-weight: 600;
        list-style: none;
    }
    .product-category-accordion .accordion-link:hover,
    .product-category-accordion .accordion-toggle:hover,
    .product-category-accordion .accordion-item.active > .accordion-link,
    .product-category-accordion .accordion-item.active .accordion-toggle {
        color: var(--home-accent);
        background: var(--home-accent-soft);
    }
    .product-category-accordion .accordion-body {
        padding: 0 0 12px;
        background: #fafbfc;
    }
    .accordion-child-link {
        display: block;
        padding: 10px 20px 10px 32px;
        color: #667085;
        font-size: 14px;
        text-decoration: none;
    }
    .accordion-child-link:hover,
    .accordion-child-link.is-current {
        color: var(--home-accent);
        font-weight: 600;
    }
    .product-category-accordion .accordion-icon {
        transition: transform 0.2s ease;
    }
    .product-category-accordion .accordion-details[open] .accordion-icon {
        transform: rotate(180deg);
    }
    .product-category-accordion summary::-webkit-details-marker {
        display: none;
    }
    .category-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 26px;
        padding: 0 8px;
        border-radius: 999px;
        background: #eff3f8;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }
    .products-grid-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }
    .products-grid-head h2 {
        margin-bottom: 0;
    }
    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(15,23,42,0.06);
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        border: 1px solid rgba(39,68,93,0.06);
    }
    .product-card:hover {
        box-shadow: 0 12px 32px rgba(15,23,42,0.12);
        transform: translateY(-2px);
    }
    .product-card-media {
        display: block;
        position: relative;
        background: #f8f9fb;
        aspect-ratio: 4/3;
        overflow: hidden;
    }
    .product-card-image,
    .product-card-placeholder {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease, opacity 0.35s ease;
    }
    .product-card-image-main {
        position: absolute;
        inset: 0;
    }
    .product-card-image-hover {
        position: absolute;
        inset: 0;
        opacity: 0;
        z-index: 1;
    }
    .product-card-media:hover .product-card-image-hover {
        opacity: 1;
    }
    .product-card-media:hover .product-card-image-main:not(.product-card-image-hover) {
        transform: scale(1.03);
    }
    .product-card-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #94a3b8;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }
    .product-card-placeholder i {
        font-size: 28px;
        opacity: 0.6;
    }
    .product-card-placeholder span {
        font-size: 12px;
        letter-spacing: 0.02em;
    }
    .product-card-overlay {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(39,68,93,0.85);
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.02em;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .product-card-overlay i {
        margin-left: 6px;
        font-size: 12px;
    }
    .product-card-media:hover .product-card-overlay {
        opacity: 1;
    }
    .product-card-body {
        padding: 18px 20px 20px;
    }
    .product-card-brand {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--home-accent);
        margin-bottom: 8px;
    }
    .product-card-title {
        font-size: 17px;
        line-height: 1.4;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-card-title a {
        color: #1e293b;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .product-card-title a:hover {
        color: var(--home-accent);
    }
    .product-card-link {
        font-size: 13px;
        font-weight: 600;
        color: var(--home-accent);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.2s ease, color 0.2s ease;
    }
    .product-card-link:hover {
        color: #1e3a5f;
        gap: 8px;
    }
    .product-card-link i {
        font-size: 11px;
    }
    .products-empty-state {
        background: linear-gradient(180deg, #fbfcfd 0%, #f6f8fb 100%);
        border: 1px solid #e7ecf1;
        border-radius: 18px;
        padding: 56px 28px;
    }
    .products-empty-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--home-accent-soft);
        color: var(--home-accent);
        font-size: 28px;
        margin-bottom: 20px;
    }
    .products-empty-state h3 {
        margin-bottom: 10px;
    }
    .products-empty-state p {
        max-width: 460px;
        margin: 0 auto 24px;
        color: #667085;
    }
    @media (max-width: 991px) {
        .products-catalog-hero {
            padding: 56px 0 42px;
        }
        .product-category-panel {
            position: static;
            margin-bottom: 28px;
        }
    }
    @media (max-width: 767px) {
        .products-grid-head {
            flex-direction: column;
            align-items: flex-start;
        }
        .product-card-body {
            padding: 16px 18px 18px;
        }
        .product-card-title {
            font-size: 16px;
        }
    }
</style>
@endpush
