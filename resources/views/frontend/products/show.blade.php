@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@php
    $isEn = app()->getLocale() === 'en';
    $homeRoute = $isEn ? 'home.en' : 'home';
    $productsIndexRoute = $isEn ? 'products.en.index' : 'products.index';
    $productsShowRoute = $isEn ? 'products.en.show' : 'products.show';
    $nameField = $isEn ? 'name_en' : 'name_tr';
    $slugField = $isEn ? 'slug_en' : 'slug_tr';
    $subtitleField = $isEn ? 'subtitle_en' : 'subtitle_tr';
    $shortDescField = $isEn ? 'short_description_en' : 'short_description_tr';
    $descField = $isEn ? 'description_en' : 'description_tr';
    $seoTitleField = $isEn ? 'seo_title_en' : 'seo_title_tr';
    $seoDescField = $isEn ? 'seo_description_en' : 'seo_description_tr';

    $productName = $product->{$nameField} ?? $product->name_tr;
    $productSubtitle = $product->{$subtitleField} ?? $product->subtitle_tr;
    $productShortDesc = $product->{$shortDescField} ?? $product->short_description_tr;
    $productDescription = $product->{$descField} ?? $product->description_tr;
    $categoryName = $product->category ? ($product->category->{$nameField} ?? $product->category->name_tr) : null;
    $categorySlug = $product->category ? ($product->category->{$slugField} ?? $product->category->slug_tr) : null;

    $seoTitle = $product->{$seoTitleField} ?? $product->seo_title_tr ?? $productName;
    $seoDesc = $product->{$seoDescField}
        ?? $product->seo_description_tr
        ?? Str::limit(strip_tags($productShortDesc ?: $productDescription ?: ''), 160);

    $keywords = trim($productName . ', ' . ($categoryName ?? '') . ', metal kesim, şerit testere, endüstriyel makina');
    $productUrl = route($productsShowRoute, $product->{$slugField} ?? $product->slug_tr);

    $imageItems = $product->media ? $product->media->where('media_type', 'image') : collect();
    $videoItems = $product->media ? $product->media->where('media_type', 'video') : collect();
    $primaryImagePath = $product->thumbnail ?: ($imageItems->first()->path ?? null);
    $productImage = $primaryImagePath ? asset('storage/' . $primaryImagePath) : null;
    $contactUrl = $isEn ? route('contact.index.locale', ['locale' => 'en']) : route('contact.index.tr');
    $hasDescription = filled(trim(strip_tags((string) ($productDescription ?? ''))));
    $hasVideos = $videoItems->isNotEmpty();
    $hasDocuments = $product->documents && $product->documents->isNotEmpty();
    $specsJson = [];
    $hasRelationSpecifications = $product->specifications->isNotEmpty();

    if (($product->specifications_tr && count($product->specifications_tr) > 0) || ($product->specifications_en && count($product->specifications_en) > 0)) {
        $specsJson = $isEn && $product->specifications_en ? $product->specifications_en : ($product->specifications_tr ?? []);
    }

    $hasJsonSpecifications = !empty($specsJson);
    $hasSpecifications = $hasRelationSpecifications || $hasJsonSpecifications;
    $detailTabs = [];

    if ($hasDescription) {
        $detailTabs['description'] = __('products.detail.description');
    }

    if ($hasSpecifications) {
        $detailTabs['specs'] = __('products.detail.technical_specs');
    }

    if ($hasVideos) {
        $detailTabs['video'] = __('products.detail.video');
    }

    if ($hasDocuments) {
        $detailTabs['documents'] = __('products.detail.documents');
    }

    $activeDetailTab = array_key_first($detailTabs);
@endphp

@section('title', $seoTitle . ' | ' . ($settings->site_title ?? 'POWERCUT'))
@section('meta_description', $seoDesc)
@section('meta_keywords', $keywords)
@section('canonical', $productUrl)

@push('head')
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    @if($productImage)
    <meta property="og:image" content="{{ $productImage }}">
    @endif
    <meta property="og:url" content="{{ $productUrl }}">
    <meta property="og:type" content="product">
    <meta property="product:brand" content="{{ $product->brand?->name ?? $settings->site_title ?? 'POWERCUT' }}">
    @if($categoryName)
    <meta property="product:category" content="{{ $categoryName }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    @if($productImage)
    <meta name="twitter:image" content="{{ $productImage }}">
    @endif

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Product",
        "name": "{{ addslashes($productName) }}",
        "description": "{{ addslashes($seoDesc) }}",
        @if($productImage)
        "image": "{{ $productImage }}",
        @endif
        "url": "{{ $productUrl }}",
        @if($categoryName)
        "category": "{{ addslashes($categoryName) }}",
        @endif
        "brand": {
            "@@type": "Brand",
            "name": "{{ addslashes($product->brand?->name ?? $settings->site_title ?? 'POWERCUT') }}"
        }
    }
    </script>
@endpush

@section('content')
<section id="page-title" class="product-detail-hero">
    <div class="container">
        <div class="breadcrumb m-b-20">
            <ul>
                <li><a href="{{ route($homeRoute) }}">{{ __('products.detail.home') }}</a></li>
                <li><a href="{{ route($productsIndexRoute) }}">{{ __('products.detail.products') }}</a></li>
                @if($categoryName && $categorySlug)
                <li><a href="{{ route($productsIndexRoute, $categorySlug) }}">{{ $categoryName }}</a></li>
                @endif
                <li class="active"><a href="#">{{ $productName }}</a></li>
            </ul>
        </div>

        <div class="product-detail-hero-copy">
            <span class="product-detail-eyebrow">{{ __('products.detail.products') }}</span>
            <h1>{{ $productName }}</h1>
            @if($productSubtitle)
                <p class="lead">{{ $productSubtitle }}</p>
            @elseif($categoryName)
                <p class="lead">{{ $categoryName }}</p>
            @endif
        </div>
    </div>
    <div class="shape-divider" data-style="1"></div>
</section>

<section class="p-t-40 p-b-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="product-detail-media-card">
                    @if($imageItems->isNotEmpty() || $product->thumbnail)
                        <div class="carousel dots-inside arrows-visible product-detail-carousel" data-items="1">
                            @if($imageItems->isNotEmpty())
                                @foreach($imageItems->values() as $mediaIndex => $media)
                                <div>
                                    <x-webp-image
                                        :src="asset('storage/' . $media->path)"
                                        :alt="$media->alt_text ?? $productName"
                                        class="product-detail-main-img"
                                        :loading="$mediaIndex === 0 ? 'eager' : 'lazy'"
                                        decoding="async"
                                    />
                                </div>
                                @endforeach
                            @else
                                <div>
                                    <x-webp-image
                                        :src="asset('storage/' . $product->thumbnail)"
                                        :alt="$productName"
                                        class="product-detail-main-img"
                                        loading="eager"
                                        decoding="async"
                                    />
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="product-detail-placeholder">
                            <i class="icon-camera" aria-hidden="true"></i>
                            <span>{{ __('products.detail.gallery_placeholder') }}</span>
                        </div>
                    @endif
                </div>

            </div>

            <div class="col-lg-5">
                <div class="product-detail-summary-card">
                    <div class="product-detail-summary-top">
                        @if($product->brand)
                        <span class="product-detail-badge">{{ $product->brand->name }}</span>
                        @endif
                        @if($categoryName)
                        <span class="product-detail-badge">{{ $categoryName }}</span>
                        @endif
                        @if($product->sku)
                        <div class="product-detail-meta-row">
                            <span>{{ __('products.detail.product_code') }}</span>
                            <strong>{{ $product->sku }}</strong>
                        </div>
                        @endif
                    </div>

                    <h2>{{ $productName }}</h2>

                    @if($productSubtitle)
                    <p class="product-detail-subtitle">{{ $productSubtitle }}</p>
                    @endif

                    @if($productShortDesc)
                    <div class="product-detail-intro">
                        <h4>{{ __('products.detail.overview') }}</h4>
                        <p>{{ $productShortDesc }}</p>
                    </div>
                    @endif

                    <div class="product-detail-actions">
                        @if(!empty($settings->phone))
                        <a class="btn btn-primary" href="tel:{{ preg_replace('/\D+/', '', $settings->phone) }}">
                            <i class="icon-phone-call"></i> {{ __('products.detail.contact_us') }}
                        </a>
                        @endif
                        @if(!empty($settings->whatsapp_phone))
                        <a class="btn btn-light" target="_blank" rel="noopener" href="https://wa.me/{{ preg_replace('/\D+/', '', $settings->whatsapp_phone) }}?text={{ urlencode($productName) }}">
                            <i class="fab fa-whatsapp"></i> {{ __('products.detail.whatsapp') }}
                        </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        @if(!empty($detailTabs))
        <div class="product-detail-section-card m-t-32">
            <div class="product-detail-tab-nav" role="tablist" aria-label="{{ __('products.detail.products') }}">
                @foreach($detailTabs as $tabKey => $tabLabel)
                <button
                    type="button"
                    class="product-detail-tab-button {{ $tabKey === $activeDetailTab ? 'is-active' : '' }}"
                    data-tab-target="{{ $tabKey }}"
                    role="tab"
                    aria-selected="{{ $tabKey === $activeDetailTab ? 'true' : 'false' }}"
                >
                    {{ $tabLabel }}
                </button>
                @endforeach
            </div>

            <div class="product-detail-tab-content">
                @if($hasDescription)
                <div class="product-detail-tab-pane {{ $activeDetailTab === 'description' ? 'is-active' : '' }}" data-tab-pane="description">
                    <div class="product-detail-description">
                        {!! $productDescription !!}
                    </div>
                </div>
                @endif

                @if($hasSpecifications)
                <div class="product-detail-tab-pane {{ $activeDetailTab === 'specs' ? 'is-active' : '' }}" data-tab-pane="specs">
                    @if($hasRelationSpecifications)
                        @php $grouped = $product->specifications->groupBy('group'); @endphp
                        @foreach($grouped as $groupName => $specs)
                            @if($groupName)
                            <h4 class="product-spec-group-title">{{ $groupName }}</h4>
                            @endif
                            <div class="table-responsive m-b-20">
                                <table class="table product-spec-table">
                                    <tbody>
                                        @foreach($specs as $spec)
                                        <tr>
                                            <th>{{ $spec->name }}</th>
                                            <td>{{ $spec->value }}@if($spec->unit) {{ $spec->unit }}@endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @elseif($hasJsonSpecifications)
                        <div class="table-responsive">
                            <table class="table product-spec-table">
                                <tbody>
                                    @foreach($specsJson as $label => $value)
                                    <tr>
                                        <th>{{ $label }}</th>
                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif

                @if($hasVideos)
                <div class="product-detail-tab-pane {{ $activeDetailTab === 'video' ? 'is-active' : '' }}" data-tab-pane="video">
                    <div class="row">
                        @foreach($videoItems as $media)
                        <div class="col-md-6 m-b-20">
                            <div class="product-video-frame">
                                <iframe src="{{ $media->path }}" allowfullscreen title="{{ $media->alt_text ?? $productName }}"></iframe>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($hasDocuments)
                <div class="product-detail-tab-pane {{ $activeDetailTab === 'documents' ? 'is-active' : '' }}" data-tab-pane="documents">
                    <div class="product-document-list">
                        @foreach($product->documents as $doc)
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" rel="noopener" class="product-document-item">
                            <span class="product-document-copy">
                                <strong>{{ $doc->title ?? __('products.detail.document') }}</strong>
                                <small>{{ __('products.detail.download') }}</small>
                            </span>
                            <i class="icon-download"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="product-detail-section-card product-detail-quote-card m-t-32">
            <div class="product-detail-section-head">
                <h3>{{ __('products.detail.quote_info') }}</h3>
            </div>
            <p class="product-detail-muted">{{ __('products.detail.quote_desc') }}</p>
            <a class="btn btn-primary m-t-10" href="{{ $contactUrl }}">
                <i class="icon-mail"></i> {{ __('products.detail.contact_form') }}
            </a>
        </div>

        @if($related->isNotEmpty())
        <div class="m-t-50">
            <div class="product-detail-section-head text-center m-b-25">
                <h3>{{ __('products.detail.related') }}</h3>
            </div>
            <div class="row">
                @foreach($related as $item)
                    @php
                        $itemName = $item->{$nameField} ?? $item->name_tr;
                        $itemFirstImg = $item->media?->firstWhere('media_type', 'image');
                        $itemImg = $item->thumbnail
                            ? asset('storage/' . $item->thumbnail)
                            : ($itemFirstImg && $itemFirstImg->path ? asset('storage/' . $itemFirstImg->path) : null);
                    @endphp

                    <div class="col-md-6 col-xl-3 m-b-30">
                        <article class="related-product-card h-100">
                            <a href="{{ route($productsShowRoute, $item->{$slugField} ?? $item->slug_tr) }}" class="related-product-media">
                                @if($itemImg)
                                    <x-webp-image :src="$itemImg" :alt="$itemName" loading="lazy" decoding="async" class="related-product-img" />
                                @else
                                    <div class="related-product-placeholder">
                                        <i class="icon-camera" aria-hidden="true"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="related-product-body">
                                <h4><a href="{{ route($productsShowRoute, $item->{$slugField} ?? $item->slug_tr) }}">{{ $itemName }}</a></h4>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@push('styles')
<style>
    :root {
        --home-accent: #27445d;
        --product-detail-accent-soft: rgba(39,68,93,0.08);
        --product-detail-accent-border: rgba(39,68,93,0.18);
        --product-detail-surface: #f6f8fb;
        --product-detail-border: #e3e8ef;
    }
    .product-detail-hero {
        background: linear-gradient(120deg, rgba(13,27,42,0.97) 0%, rgba(21,36,52,0.94) 58%, rgba(39,68,93,0.22) 100%);
        color: #fff;
        padding: 58px 0 46px;
        position: relative;
    }
    .product-detail-hero .breadcrumb ul li,
    .product-detail-hero .breadcrumb ul li a {
        color: rgba(255,255,255,0.78);
    }
    .product-detail-hero .breadcrumb ul li.active a,
    .product-detail-hero .breadcrumb ul li a:hover {
        color: #fff;
    }
    .product-detail-eyebrow {
        display: inline-block;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.72);
    }
    .product-detail-hero h1 {
        color: #fff;
        font-size: 42px;
        line-height: 1.15;
        margin-bottom: 14px;
    }
    .product-detail-hero .lead {
        color: rgba(255,255,255,0.84);
        margin-bottom: 0;
        max-width: 720px;
    }
    .product-detail-media-card,
    .product-detail-summary-card,
    .product-detail-section-card,
    .related-product-card {
        background: #fff;
        border: 1px solid var(--product-detail-border);
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15,23,42,0.045);
    }
    .product-detail-media-card {
        overflow: hidden;
    }
    .product-detail-carousel {
        background: linear-gradient(180deg, #f7f9fb 0%, #eef2f6 100%);
    }
    .product-detail-main-img,
    .product-detail-placeholder {
        width: 100%;
        height: 500px;
    }
    .product-detail-main-img {
        object-fit: contain;
        display: block;
        background: #f7f9fb;
    }
    .product-detail-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #667085;
        background: linear-gradient(135deg, #122030 0%, #243447 100%);
    }
    .product-detail-placeholder i {
        font-size: 42px;
        color: rgba(255,255,255,0.75);
    }
    .product-detail-placeholder span {
        color: rgba(255,255,255,0.78);
    }
    .product-detail-summary-card {
        padding: 28px;
        height: 100%;
    }
    .product-detail-summary-top {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px 14px;
        margin-bottom: 18px;
    }
    .product-detail-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: var(--product-detail-accent-soft);
        border: 1px solid var(--product-detail-accent-border);
        color: var(--home-accent);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .product-detail-meta-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #667085;
    }
    .product-detail-meta-row strong {
        color: #101828;
        font-weight: 700;
    }
    .product-detail-summary-card h2 {
        font-size: 34px;
        line-height: 1.2;
        margin-bottom: 12px;
    }
    .product-detail-subtitle {
        color: #475467;
        font-size: 18px;
        line-height: 1.65;
        margin-bottom: 22px;
    }
    .product-detail-intro {
        padding: 20px 22px;
        border-radius: 14px;
        background: var(--product-detail-surface);
        border: 1px solid #e8edf3;
    }
    .product-detail-intro h4 {
        margin-bottom: 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #667085;
    }
    .product-detail-intro p {
        margin-bottom: 0;
        color: #475467;
        line-height: 1.75;
    }
    .product-detail-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }
    .product-detail-section-card {
        padding: 28px;
    }
    .product-detail-tab-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-bottom: 22px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e8edf3;
    }
    .product-detail-tab-button {
        padding: 10px 16px;
        border: 1px solid #d8e0e8;
        border-radius: 999px;
        background: #fff;
        color: #475467;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .product-detail-tab-button:hover,
    .product-detail-tab-button:focus {
        border-color: var(--product-detail-accent-border);
        color: var(--home-accent);
        outline: none;
    }
    .product-detail-tab-button.is-active {
        background: var(--product-detail-accent-soft);
        border-color: var(--product-detail-accent-border);
        color: var(--home-accent);
    }
    .product-detail-tab-pane {
        display: none;
    }
    .product-detail-tab-pane.is-active {
        display: block;
    }
    .product-detail-section-head h3 {
        margin-bottom: 18px;
        font-size: 26px;
    }
    .product-detail-description {
        color: #475467;
        line-height: 1.8;
    }
    .product-detail-description > *:last-child {
        margin-bottom: 0;
    }
    .product-detail-description img {
        max-width: 100%;
        height: auto;
    }
    .product-spec-group-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #667085;
        margin: 4px 0 12px;
    }
    .product-spec-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .product-spec-table th,
    .product-spec-table td {
        padding: 14px 16px;
        border-top: 0;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }
    .product-spec-table th {
        width: 42%;
        color: #101828;
        font-weight: 600;
        background: var(--product-detail-surface);
    }
    .product-spec-table td {
        color: #475467;
    }
    .product-detail-muted {
        color: #667085;
        line-height: 1.75;
        margin-bottom: 0;
    }
    .product-detail-side-card .btn-block {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-detail-quote-card {
        text-align: center;
        padding-top: 2rem;
    }
    .product-detail-quote-card .product-detail-muted {
        max-width: 760px;
        margin: 0 auto;
    }
    .product-detail-quote-card .btn {
        min-width: 220px;
    }
    .product-document-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .product-document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        border: 1px solid #e6ebf1;
        border-radius: 14px;
        color: #101828;
        text-decoration: none;
        background: #fbfcfd;
        transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    .product-document-item:hover {
        border-color: var(--product-detail-accent-border);
        background: var(--product-detail-surface);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15,23,42,0.05);
        color: var(--home-accent);
    }
    .product-document-copy {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .product-document-copy small {
        color: #667085;
    }
    .product-video-frame {
        position: relative;
        padding-top: 56.25%;
        border-radius: 16px;
        overflow: hidden;
        background: #0f172a;
    }
    .product-video-frame iframe {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    .related-product-card {
        overflow: hidden;
        height: 100%;
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }
    .related-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 34px rgba(15,23,42,0.08);
    }
    .related-product-media {
        display: block;
        background: linear-gradient(180deg, #f7f9fb 0%, #eef2f6 100%);
    }
    .related-product-img,
    .related-product-placeholder {
        width: 100%;
        height: 220px;
    }
    .related-product-img {
        object-fit: cover;
        display: block;
    }
    .related-product-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #122030 0%, #243447 100%);
        color: rgba(255,255,255,0.72);
    }
    .related-product-placeholder i {
        font-size: 34px;
    }
    .related-product-body {
        padding: 20px 20px 22px;
    }
    .related-product-body h4 {
        margin-bottom: 0;
        font-size: 19px;
        line-height: 1.4;
    }
    .related-product-body h4 a {
        color: #101828;
    }
    .related-product-body h4 a:hover {
        color: var(--home-accent);
    }
    .product-detail-actions .btn,
    .product-detail-side-card .btn {
        border-radius: 10px;
        font-weight: 600;
        box-shadow: none;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    .product-detail-actions .btn-primary,
    .product-detail-side-card .btn-primary {
        background: #1f3447;
        border-color: #1f3447;
        color: #fff;
    }
    .product-detail-actions .btn-primary:hover,
    .product-detail-actions .btn-primary:focus,
    .product-detail-side-card .btn-primary:hover,
    .product-detail-side-card .btn-primary:focus {
        background: #27445d;
        border-color: #27445d;
        color: #fff;
    }
    .product-detail-actions .btn-light {
        background: #fff;
        border-color: #d6dde6;
        color: #1f3447;
    }
    .product-detail-actions .btn-light:hover,
    .product-detail-actions .btn-light:focus {
        background: var(--product-detail-surface);
        border-color: var(--product-detail-accent-border);
        color: #27445d;
    }
    @media (max-width: 991px) {
        .product-detail-hero {
            padding: 48px 0 38px;
        }
        .product-detail-hero h1 {
            font-size: 34px;
        }
        .product-detail-main-img,
        .product-detail-placeholder {
            height: 380px;
        }
        .product-detail-summary-card {
            margin-top: 24px;
        }
    }
    @media (max-width: 767px) {
        .product-detail-main-img,
        .product-detail-placeholder,
        .related-product-img,
        .related-product-placeholder {
            height: 260px;
        }
        .product-detail-summary-card,
        .product-detail-section-card {
            padding: 22px;
        }
        .product-detail-section-head h3 {
            font-size: 22px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('.product-detail-tab-button');
        const tabPanes = document.querySelectorAll('.product-detail-tab-pane');

        if (!tabButtons.length || !tabPanes.length) {
            return;
        }

        tabButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const target = button.getAttribute('data-tab-target');

                tabButtons.forEach(function (item) {
                    const isActive = item === button;
                    item.classList.toggle('is-active', isActive);
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                tabPanes.forEach(function (pane) {
                    pane.classList.toggle('is-active', pane.getAttribute('data-tab-pane') === target);
                });
            });
        });
    });
</script>
@endpush
@endsection
