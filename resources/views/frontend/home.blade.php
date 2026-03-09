@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)

@php
    $locale = app()->getLocale();
    $isEn = $locale === 'en';

    $categoryNameField = $isEn ? 'name_en' : 'name_tr';
    $categorySlugField = $isEn ? 'slug_en' : 'slug_tr';
    $categorySubtitleField = $isEn ? 'subtitle_en' : 'subtitle_tr';
    $categoryDescField = $isEn ? 'description_en' : 'description_tr';

    $productNameField = $isEn ? 'name_en' : 'name_tr';
    $productSubtitleField = $isEn ? 'subtitle_en' : 'subtitle_tr';
    $productShortDescField = $isEn ? 'short_description_en' : 'short_description_tr';
    $productDescField = $isEn ? 'description_en' : 'description_tr';

    $productsUrl = route('products.index');
    $contactUrl = $isEn ? route('contact.index.locale', ['locale' => 'en']) : route('contact.index.tr');
    $aboutUrl = $isEn ? route('about.en.index') : route('about.index');
    $phoneHref = !empty($settings->phone) ? 'tel:' . preg_replace('/\D+/', '', $settings->phone) : null;
    $whatsappHref = !empty($settings->whatsapp_phone) ? 'https://wa.me/' . preg_replace('/\D+/', '', $settings->whatsapp_phone) : null;
@endphp

@push('styles')
<style>
    @keyframes saw-blade-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes saw-blade-spin-centered {
        from { transform: translate(-50%, -50%) rotate(0deg); }
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
    @keyframes saw-cut-line {
        0% { transform: scaleX(0); transform-origin: left; opacity: 1; }
        100% { transform: scaleX(1); transform-origin: left; opacity: 1; }
    }
    :root {
        --home-accent: #e65100;
        --home-navy: #0d1b2a;
        --home-anthracite: #1b2838;
    }
    .home-hero .slide-captions { text-align: left !important; position: relative; }
    .home-hero .slide-captions h1 {
        font-weight: 700;
        letter-spacing: 0.02em;
        font-size: 3rem;
        line-height: 1.2;
        font-family: inherit;
    }
    .home-hero .slide-captions .lead { font-size: 1.25rem; max-width: 540px; }
    .home-hero .btn-primary {
        background: var(--home-accent);
        border: none;
        font-weight: 600;
        padding: 14px 28px;
        transition: all 0.3s ease;
    }
    .home-hero .btn-primary:hover {
        box-shadow: 0 6px 20px rgba(230, 81, 0, 0.45);
        transform: translateY(-2px);
    }
    .home-hero .slide-captions h1::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: var(--home-accent);
        margin-top: 1rem;
        transform: scaleX(0);
        transform-origin: left;
        animation: saw-cut-line 1.2s ease-out 0.5s forwards;
    }
    .home-section-dark {
        background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 50%, #0d0d0d 100%);
    }
    .home-service-box,
    .home-industry-box,
    .home-reference-box {
        height: 100%;
    }
    .home-heading-agency h2 {
        font-size: 1.5rem;
        line-height: 1.4;
    }
    @media (max-width: 991px) {
        .home-heading-agency h2 {
            font-size: 1.10rem;
        }
    }
    .home-catalog-card,
    .home-featured-card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        height: 100%;
    }
    .home-catalog-card {
        position: relative;
        min-height: 380px;
        border-radius: 10px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.16);
        background: #0f172a;
    }
    .home-catalog-card .portfolio-item-wrap {
        position: relative;
        min-height: 380px;
        height: 100%;
    }
    .home-featured-card .portfolio-item-wrap {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .home-catalog-card .portfolio-image {
        position: absolute;
        inset: 0;
        height: 100%;
    }
    .home-catalog-card .portfolio-image > a,
    .home-catalog-card .portfolio-image img {
        display: block;
        width: 100%;
        height: 100%;
    }
    .home-catalog-card .portfolio-image::after {
        content: '';
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(180deg, rgba(13, 27, 42, 0.08) 0%, rgba(13, 27, 42, 0.42) 40%, rgba(13, 27, 42, 0.96) 100%);
    }
    .home-catalog-card .portfolio-image img {
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .home-catalog-card:hover .portfolio-image img {
        transform: scale(1.05);
    }
    .home-catalog-card .portfolio-description {
        position: absolute !important;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
        padding: 1.75rem;
        background: linear-gradient(180deg, rgba(13, 27, 42, 0) 0%, rgba(13, 27, 42, 0.2) 20%, rgba(13, 27, 42, 0.88) 100%);
        display: flex;
        flex-direction: column;
        min-height: 205px;
    }
    .home-featured-carousel {
        padding: 0 4px 2rem;
    }
    .home-featured-carousel .polo-carousel-item {
        padding-top: 4px;
        padding-bottom: 12px;
    }
    .home-featured-carousel .flickity-viewport {
        overflow: visible;
    }
    .home-featured-carousel .flickity-button {
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16);
    }
    .home-featured-carousel .flickity-button:hover {
        background: #1b2838;
    }
    .home-featured-carousel .flickity-page-dots {
        bottom: -8px;
    }
    .home-catalog-card .portfolio-description h3,
    .home-featured-card .portfolio-description h3 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .home-catalog-card .portfolio-description h3 {
        font-size: 1.35rem;
        margin-bottom: 0.65rem;
        letter-spacing: 0.01em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .home-catalog-card .portfolio-description h3 a {
        color: #fff;
    }
    .home-featured-card .portfolio-description h3 a {
        color: #1a1a1a;
    }
    .home-catalog-card .catalog-desc,
    .home-featured-card .catalog-desc {
        font-size: 0.9rem;
        margin: 0.5rem 0 0.9rem;
        line-height: 1.6;
    }
    .home-catalog-card .catalog-desc {
        color: rgba(255,255,255,0.82);
        margin-bottom: 1.15rem;
        max-width: 92%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .home-featured-card .catalog-desc {
        color: #6b7280;
    }
    .home-catalog-card .catalog-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        width: 100%;
        margin-top: auto;
    }
    .home-catalog-card .catalog-actions .btn {
        white-space: nowrap;
        transition: all 0.3s ease;
    }
    .home-catalog-card .catalog-actions .btn:hover,
    .home-catalog-card .catalog-actions .btn:focus {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #0d1b2a !important;
    }
    .home-catalog-card .catalog-count {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.8rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.18);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1;
    }
    .home-category-carousel {
        padding: 0 4px 3rem;
    }
    .home-category-carousel .polo-carousel-item {
        padding-top: 4px;
        padding-bottom: 12px;
    }
    .home-category-slide {
        height: 100%;
    }
    .home-category-carousel .flickity-viewport {
        overflow: visible;
    }
    .home-category-carousel .flickity-button {
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16);
    }
    .home-category-carousel .flickity-button:hover {
        background: #1b2838;
    }
    .home-category-carousel .flickity-page-dots {
        bottom: -8px;
    }
    .home-catalog-card .portfolio-image .bg-dark,
    .home-featured-card .portfolio-image .bg-dark,
    .home-reference-logo {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
    }
    .home-catalog-card .portfolio-image .home-catalog-placeholder {
        align-items: flex-start !important;
        padding-top: 4rem;
    }
    .home-catalog-card .portfolio-image .home-catalog-placeholder i {
        opacity: 0.22 !important;
        animation: saw-blade-spin 18s linear infinite;
    }
    .home-service-box.effect.medium.border,
    .home-industry-box.effect.medium.border,
    .home-reference-box.effect.medium.border {
        transition: all 0.3s ease;
        border-color: #e8ecf0;
        background: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border-radius: 8px;
    }
    .home-service-box.effect.medium.border {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2.25rem 1.75rem !important;
        text-align: center;
    }
    .home-service-box.effect.medium.border:hover,
    .home-industry-box.effect.medium.border:hover,
    .home-reference-box.effect.medium.border:hover {
        border-color: rgba(230, 81, 0, 0.35) !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(0,0,0,0.08);
    }
    .home-service-box .icon,
    .home-industry-box .icon,
    .home-reference-box .icon {
        width: 74px;
        height: 74px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 100%);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    }
    .home-service-box .icon i,
    .home-industry-box .icon i,
    .home-reference-box .icon i {
        color: #fff !important;
        font-size: 1.5rem;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .home-service-box h3 {
        min-height: 3.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.9rem;
    }
    .home-service-box p {
        width: 100%;
        margin-bottom: 1.4rem;
        line-height: 1.75;
        color: #64748b !important;
    }
    .home-service-box .home-service-spacer {
        flex: 1;
        width: 100%;
    }
    .home-service-box .home-service-cta {
        margin-top: auto;
        align-self: center;
    }
    .home-why-box .home-why-card {
        height: 100%;
        padding: 2rem 1.5rem;
        border-radius: 10px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .home-why-box .home-why-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #e65100 0%, #FFA500 100%);
    }
    .home-why-box .home-why-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255,255,255,0.22);
        background: rgba(255,255,255,0.09);
        box-shadow: 0 16px 36px rgba(0,0,0,0.18);
    }
    .home-why-box .home-why-icon {
        width: 64px;
        height: 64px;
        margin-bottom: 1.25rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.14);
    }
    .home-why-box .home-why-icon i {
        color: #fff;
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }
    .home-why-box .home-why-card h3 {
        color: #ffffff !important;
        font-size: 1.15rem;
        margin-bottom: 0.75rem;
    }
    .home-why-box .home-why-card p,
    .home-support-section p,
    .home-support-section .lead,
    .home-cta-section p {
        color: rgba(255,255,255,0.82) !important;
    }
    .home-why-box .home-why-card:hover .home-why-icon i {
        color: #FFA500 !important;
    }
    .home-support-panel,
    .home-cta-panel {
        border: 1px solid rgba(255,255,255,0.14);
        background: rgba(255,255,255,0.04);
        border-radius: 8px;
        padding: 2.5rem;
    }
    .home-support-list .icon-box.effect.small.clean {
        margin-bottom: 1.5rem;
    }
    .home-support-list .icon-box.effect.small.clean:last-child {
        margin-bottom: 0;
    }
    .home-reference-box {
        padding: 2rem 1rem !important;
    }
    .home-reference-logo {
        min-height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        padding: 1rem;
    }
    .home-reference-logo img {
        max-width: 100%;
        max-height: 58px;
        object-fit: contain;
        filter: grayscale(1);
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    .home-reference-box:hover .home-reference-logo img {
        filter: grayscale(0);
        opacity: 1;
    }
    .home-reference-name {
        margin-top: 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    .home-cta-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--home-anthracite) 0%, var(--home-navy) 100%);
        padding: 4rem 0;
    }
    .home-section-divider {
        height: 1.5rem;
        background: linear-gradient(180deg, #e8ecf0 0%, #f1f4f8 100%);
    }
    .home-cta-saw-blades {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 0;
    }
    .home-cta-saw-blade {
        position: absolute;
        width: var(--sb-size, 48px);
        height: var(--sb-size, 48px);
        left: var(--sb-x);
        top: var(--sb-y);
        transform-origin: center center;
        background: url('{{ asset("assets/images/saw-blade-bg.svg") }}') center/contain no-repeat;
        opacity: 0.38;
        animation: saw-blade-spin-centered var(--sb-dur, 25s) linear infinite;
    }
    .home-cta-section .container {
        position: relative;
        z-index: 1;
    }
    .home-cta-section .btn-primary {
        background: var(--home-accent);
        border: none;
        padding: 14px 28px;
        font-weight: 600;
    }
    .home-cta-section .btn-outline-light {
        border: 2px solid rgba(255,255,255,0.45);
        color: #fff;
    }
    .home-cta-section .btn-outline-light:hover {
        background: var(--home-accent);
        border-color: var(--home-accent);
        color: #fff;
    }
    @media (prefers-reduced-motion: reduce) {
        .home-catalog-card .portfolio-image .bg-dark i,
        .home-hero .slide-captions h1::after,
        .home-cta-saw-blade {
            animation: none !important;
        }
    }
    @media (max-width: 991px) {
        .home-catalog-card,
        .home-catalog-card .portfolio-item-wrap {
            min-height: 340px;
        }
        .home-category-carousel,
        .home-featured-carousel {
            padding-bottom: 2.5rem;
        }
        .home-catalog-card .portfolio-description {
            padding: 1.35rem;
            min-height: 190px;
        }
        .home-catalog-card .catalog-desc {
            max-width: 100%;
        }
        .home-catalog-card .catalog-actions {
            flex-direction: column;
            align-items: center;
        }
        .home-hero .slide-captions { text-align: center !important; }
        .home-hero .slide-captions .lead { margin-left: auto; margin-right: auto; }
        .home-support-panel,
        .home-cta-panel {
            padding: 2rem;
        }
    }
</style>
@endpush

@section('content')
    {{-- Hero: Polo header+slider için #slider header'ın hemen kardeşi olmalı --}}
    @if(!empty($sliderData) && is_array($sliderData))
    <div id="slider" class="inspiro-slider slider-halfscreen dots-creative home-hero"
         data-mouse-drag="true" data-touch-drag="true" data-loop="true" data-autoplay="true"
         data-autoplay-timeout="12000" data-items="1" data-nav="true" data-height-xs="360">
        @foreach($sliderData as $slide)
        <div class="slide {{ $slide['animation'] ?? 'kenburns' }}"
             @if(!empty($slide['type']) && $slide['type'] === 'image' && !empty($slide['image_path']))
                data-bg-image="{{ asset('storage/' . $slide['image_path']) }}"
             @elseif(!empty($slide['type']) && $slide['type'] === 'video' && !empty($slide['video_path']))
                data-bg-video="{{ asset('storage/' . $slide['video_path']) }}"
             @endif>
            <div class="bg-overlay" style="opacity: 0.55; background: linear-gradient(90deg, rgba(13,27,42,0.85) 0%, rgba(13,27,42,0.4) 100%);"></div>
            <div class="container">
                <div class="slide-captions text-light">
                    @if(!empty($slide['title_tr']))
                        <h1 data-caption-animate="zoom-out">{{ $isEn ? ($slide['title_en'] ?? $slide['title_tr']) : $slide['title_tr'] }}</h1>
                    @endif
                    @if(!empty($slide['subtitle_tr']))
                        <p class="lead">{{ $isEn ? ($slide['subtitle_en'] ?? $slide['subtitle_tr']) : $slide['subtitle_tr'] }}</p>
                    @endif
                    @if(!empty($slide['button_text_tr']) && !empty($slide['button_link']))
                        <div><a href="{{ $slide['button_link'] }}" class="btn btn-primary">{{ $isEn ? ($slide['button_text_en'] ?? $slide['button_text_tr']) : $slide['button_text_tr'] }}</a></div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Varsayılan: Tek sabit hero - yüksek kaliteli makine görseli hissi --}}
    <div id="slider" class="home-hero" style="min-height: 72vh; background: url('{{ asset('assets/images/blog/blog-banner.png') }}') center/cover no-repeat; position: relative;">
        <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(13,27,42,0.88) 0%, rgba(13,27,42,0.5) 100%, transparent 30%);"></div>
        <div class="container" style="position: relative; z-index: 2; padding-top: 140px; padding-bottom: 100px;">
            <div class="slide-captions text-light">
                <h1 style="font-weight: 700; letter-spacing: 0.02em; font-size: 3rem; line-height: 1.2;">{{ __('home.hero_slogan') }}</h1>
                <p class="lead" style="font-size: 1.25rem; max-width: 540px; margin-bottom: 2rem;">{{ __('home.hero_subtitle') }}</p>
                <a href="{{ url('/urunler') }}" class="btn btn-primary">{{ __('home.hero_cta') }}</a>
            </div>
        </div>
    </div>
    @endif
    <div class="shape-divider" data-style="1" data-color="#ffffff"></div>

    {{-- Products / Machines --}}
    <section class="background-grey p-t-80 p-b-80" id="product-groups">
        <div class="container">
            @if($homeCategories->isNotEmpty())
            <div class="carousel home-category-carousel dots-creative dots-dark arrows-visibile"
                 data-items="3"
                 data-items-lg="3"
                 data-items-md="2"
                 data-items-sm="2"
                 data-items-xs="1"
                 data-margin="24"
                 data-loop="false"
                 data-group-cells="true">
                @foreach($homeCategories as $index => $category)
                @php
                    $categoryName = $category->{$categoryNameField} ?: $category->name_tr;
                    $categorySlug = $category->{$categorySlugField} ?: $category->slug_tr;
                    $categoryText = $category->{$categorySubtitleField}
                        ?: Str::limit(strip_tags($category->{$categoryDescField} ?: $category->description_tr ?: ''), 120);
                @endphp
                <div class="home-category-slide" data-animate="fadeInUp" data-animate-delay="{{ $index * 100 }}">
                    <div class="portfolio-item img-zoom home-catalog-card">
                        <div class="portfolio-item-wrap">
                            <div class="portfolio-image">
                                <a href="{{ route('products.index', $categorySlug) }}">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $categoryName }}" loading="lazy">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-dark text-light home-catalog-placeholder" style="min-height:100%; width:100%;">
                                            <i class="icon-grid" style="font-size:3.5rem; opacity:0.6;"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="portfolio-description">
                                <h3><a href="{{ route('products.index', $categorySlug) }}">{{ $categoryName }}</a></h3>
                                <p class="catalog-desc">
                                    {{ $categoryText ?: __('home.categories_subtitle') }}
                                </p>
                                <div class="catalog-actions">
                                    <a href="{{ route('products.index', $categorySlug) }}" class="btn btn-light btn-outline btn-rounded btn-sm" aria-label="{{ __('home.detail_button') }}" title="{{ __('home.detail_button') }}">
                                        {{ __('home.detail_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-light text-center mb-0">{{ __('home.featured_empty') }}</div>
            @endif
        </div>
    </section>

    {{-- Solutions / Services --}}
    <section class="p-t-80 p-b-80">
        <div class="container">
            <div class="heading-text heading-section home-heading-agency text-center m-b-50">
                <h2>{{ __('home.services_heading') }}</h2>
                <span class="lead">{{ __('home.services_subtitle') }}</span>
            </div>

            @php
                $serviceCards = [
                    [
                        'url' => $productsUrl,
                        'icon' => 'fa fa-th-large',
                        'title' => __('home.service_products'),
                        'desc' => __('home.service_products_desc'),
                        'button' => __('home.service_products_button'),
                    ],
                    [
                        'url' => $phoneHref ?: $contactUrl,
                        'icon' => 'fa fa-wrench',
                        'title' => __('home.service_technical'),
                        'desc' => __('home.service_technical_desc'),
                        'button' => __('home.service_call'),
                    ],
                    [
                        'url' => $contactUrl,
                        'icon' => 'fa fa-cogs',
                        'title' => __('home.service_spare_parts'),
                        'desc' => __('home.service_spare_parts_desc'),
                        'button' => __('home.service_spare_parts_button'),
                    ],
                ];
            @endphp

            <div class="row">
                @foreach($serviceCards as $serviceIndex => $serviceCard)
                <div class="col-lg-4 m-b-30" data-animate="fadeInUp" data-animate-delay="{{ $serviceIndex * 100 }}">
                    <a href="{{ $serviceCard['url'] }}" class="text-decoration-none text-dark">
                        <div class="icon-box effect medium border text-center home-service-box p-4">
                            <div class="icon"><i class="{{ $serviceCard['icon'] }}"></i></div>
                            <h3>{{ $serviceCard['title'] }}</h3>
                            @if(!empty($serviceCard['desc']))
                                <p class="text-muted">{{ $serviceCard['desc'] }}</p>
                            @else
                                <div class="home-service-spacer"></div>
                            @endif
                            <span class="btn btn-outline-dark btn-rounded btn-sm home-service-cta">{{ $serviceCard['button'] }} <i class="icon-chevron-right"></i></span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="home-section-dark text-light p-t-80 p-b-80" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="heading-text heading-section home-heading-agency">
                        <h2 class="text-light">{{ __('about.why_title') }}</h2>
                        @if(!empty(__('home.why_subtitle')))
                            <span class="lead">{{ __('home.why_subtitle') }}</span>
                        @endif
                    </div>
                    <p class="lead m-t-20">{{ __('about.intro_1') }}</p>
                    <a href="{{ $aboutUrl }}" class="btn btn-light m-t-20">{{ __('home.about_more') }}</a>
                </div>
                <div class="col-lg-8 home-why-box">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="home-why-card">
                                <div class="home-why-icon"><i class="fa fa-cog"></i></div>
                                <h3>{{ __('about.why_1_title') }}</h3>
                                <p>{{ __('about.why_1_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="home-why-card">
                                <div class="home-why-icon"><i class="fa fa-puzzle-piece"></i></div>
                                <h3>{{ __('about.why_2_title') }}</h3>
                                <p>{{ __('about.why_2_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="home-why-card">
                                <div class="home-why-icon"><i class="fa fa-headset"></i></div>
                                <h3>{{ __('about.why_3_title') }}</h3>
                                <p>{{ __('about.why_3_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="home-why-card">
                                <div class="home-why-icon"><i class="fa fa-leaf"></i></div>
                                <h3>{{ __('about.why_4_title') }}</h3>
                                <p>{{ __('about.why_4_text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Machines --}}
    @if($featuredProducts->isNotEmpty())
    <section id="featured-machines" class="background-grey p-t-50 p-b-50">
        <div class="container">
            <div class="heading-text heading-section home-heading-agency text-center m-b-30">
                <h2>{{ __('home.featured_title') }}</h2>
                <span class="lead">{{ __('home.featured_subtitle') }}</span>
            </div>

            <div class="carousel home-featured-carousel dots-creative dots-dark arrows-visibile"
                 data-items="4"
                 data-items-lg="4"
                 data-items-md="3"
                 data-items-sm="2"
                 data-items-xs="1"
                 data-margin="10"
                 data-loop="false"
                 data-group-cells="true">
                @foreach($featuredProducts as $index => $product)
                @php
                    $productName = $product->{$productNameField} ?: $product->name_tr;
                    $productCategory = $product->category ? ($product->category->{$categoryNameField} ?: $product->category->name_tr) : null;
                    $firstImg = $product->media?->firstWhere('media_type', 'image');
                    $productImage = $product->thumbnail
                        ? asset('storage/' . $product->thumbnail)
                        : ($firstImg && $firstImg->path ? asset('storage/' . $firstImg->path) : null);
                @endphp
                <div class="portfolio-item img-zoom">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <a href="{{ route('products.show', $product->slug_tr) }}">
                                @if($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $productName }}" loading="lazy">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-dark text-light" style="min-height:180px;">
                                        <i class="icon-camera" style="font-size:2.5rem; opacity:0.5;"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        <div class="portfolio-description">
                            <a href="{{ route('products.show', $product->slug_tr) }}">
                                <h3>{{ $productName }}</h3>
                                <span>{{ $product->brand ? $product->brand->name . ($productCategory ? ' · ' . $productCategory : '') : ($productCategory ?: __('home.detail_button')) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center m-t-20">
                <a href="{{ $productsUrl }}" class="btn btn-primary">{{ __('home.cta_products') }}</a>
            </div>
        </div>
    </section>
    @else
    {{-- İnce açık/gri şerit - iki koyu bölüm arasında görsel ayırıcı --}}
    <div class="home-section-divider"></div>
    @endif

    {{-- Contact CTA --}}
    <section class="home-cta-section text-light">
        <div class="home-cta-saw-blades" aria-hidden="true">
            <div class="home-cta-saw-blade" style="--sb-x: 8%; --sb-y: 25%; --sb-size: 72px; --sb-dur: 28s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 22%; --sb-y: 70%; --sb-size: 50px; --sb-dur: 22s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 45%; --sb-y: 18%; --sb-size: 82px; --sb-dur: 32s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 58%; --sb-y: 82%; --sb-size: 68px; --sb-dur: 26s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 78%; --sb-y: 35%; --sb-size: 74px; --sb-dur: 24s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 92%; --sb-y: 65%; --sb-size: 54px; --sb-dur: 30s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 15%; --sb-y: 48%; --sb-size: 48px; --sb-dur: 20s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 35%; --sb-y: 88%; --sb-size: 76px; --sb-dur: 27s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 68%; --sb-y: 12%; --sb-size: 64px; --sb-dur: 23s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 85%; --sb-y: 52%; --sb-size: 70px; --sb-dur: 29s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 5%; --sb-y: 55%; --sb-size: 44px; --sb-dur: 19s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 38%; --sb-y: 42%; --sb-size: 62px; --sb-dur: 25s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 72%; --sb-y: 58%; --sb-size: 49px; --sb-dur: 21s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 95%; --sb-y: 22%; --sb-size: 58px; --sb-dur: 31s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 12%; --sb-y: 85%; --sb-size: 52px; --sb-dur: 24s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 52%; --sb-y: 5%; --sb-size: 56px; --sb-dur: 18s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 88%; --sb-y: 78%; --sb-size: 78px; --sb-dur: 27s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 28%; --sb-y: 32%; --sb-size: 50px; --sb-dur: 26s;"></div>
            <div class="home-cta-saw-blade" style="--sb-x: 62%; --sb-y: 45%; --sb-size: 66px; --sb-dur: 29s;"></div>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start">
                    <h3 class="mb-2">{{ __('home.cta_title') }}</h3>
                    <p class="mb-0 opacity-75">{{ __('home.cta_subtitle') }}</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end m-t-20 m-t-lg-0">
                    <a href="{{ $contactUrl }}" class="btn btn-primary m-r-10">{{ __('home.cta_contact') }}</a>
                    <a href="{{ $productsUrl }}" class="btn btn-outline-light">{{ __('home.cta_products') }}</a>
                </div>
            </div>
        </div>
    </section>

@endsection
