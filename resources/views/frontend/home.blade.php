@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('meta_keywords', $seoKeywords)

@php
    $locale = app()->getLocale();
    $isEn = $locale === 'en';
    $homeUrl = $isEn ? route('home.en') : route('home');
    $productsIndexRoute = $isEn ? 'products.en.index' : 'products.index';
    $productsShowRoute = $isEn ? 'products.en.show' : 'products.show';

    $categoryNameField = $isEn ? 'name_en' : 'name_tr';
    $categorySlugField = $isEn ? 'slug_en' : 'slug_tr';
    $categorySubtitleField = $isEn ? 'subtitle_en' : 'subtitle_tr';
    $categoryDescField = $isEn ? 'description_en' : 'description_tr';

    $productNameField = $isEn ? 'name_en' : 'name_tr';
    $productSlugField = $isEn ? 'slug_en' : 'slug_tr';
    $productSubtitleField = $isEn ? 'subtitle_en' : 'subtitle_tr';
    $productShortDescField = $isEn ? 'short_description_en' : 'short_description_tr';
    $productDescField = $isEn ? 'description_en' : 'description_tr';

    $productsUrl = route($productsIndexRoute);
    $fallbackProductImage = asset('assets/images/other/400x250.webp');
    $contactUrl = $isEn ? route('contact.index.locale', ['locale' => 'en']) : route('contact.index.tr');
    $aboutUrl = $isEn ? route('about.en.index') : route('about.index');
    $phoneHref = !empty($settings->phone) ? 'tel:' . preg_replace('/\D+/', '', $settings->phone) : null;
    $whatsappHref = !empty($settings->whatsapp_phone) ? 'https://wa.me/' . preg_replace('/\D+/', '', $settings->whatsapp_phone) : null;

    $resolveMediaUrl = function (?string $path) {
        if (empty($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        if (Str::startsWith($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        return asset('storage/' . $normalizedPath);
    };

    $localizeHomeSlideUrl = function (?string $url) use ($isEn) {
        if (!$url || !$isEn) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $normalizedPath = '/' . ltrim((string) $path, '/');

        return match ($normalizedPath) {
            '/', '/en' => route('home.en'),
            '/iletisim', '/contact', '/en/contact' => route('contact.index.locale', ['locale' => 'en']),
            '/urunler', '/products', '/en/products' => route('products.en.index'),
            '/hakkimizda', '/about', '/en/about' => route('about.en.index'),
            '/kvkk', '/en/kvkk' => route('kvkk.en'),
            default => $url,
        };
    };
@endphp

@push('head')
    @if(!empty($sliderData) && is_array($sliderData))
        @php $firstSlide = $sliderData[0] ?? null; @endphp
        @if(is_array($firstSlide) && ($firstSlide['type'] ?? '') === 'image' && !empty($firstSlide['image_path']))
            <link rel="preload" as="image" href="{{ asset('storage/' . $firstSlide['image_path']) }}" fetchpriority="high">
        @endif
    @else
        <link rel="preload" as="image" href="{{ asset('assets/images/blog/blog-banner.webp') }}" fetchpriority="high">
    @endif
@endpush

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
    .home-featured-card .portfolio-image {
        position: relative;
        min-height: 220px;
        background: #eef2f7;
    }
    .home-featured-card .portfolio-image:after {
        display: none;
    }
    .home-featured-card .portfolio-image > a,
    .home-featured-card .portfolio-image picture,
    .home-featured-card .portfolio-image img {
        display: block;
        width: 100%;
        height: 100%;
    }
    .home-featured-card .portfolio-image img {
        min-height: 220px;
        object-fit: cover;
    }
    .home-featured-card .portfolio-description {
        position: static !important;
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
        width: 100% !important;
        padding: 1rem 1.25rem;
        background: #fff;
        text-align: left;
    }
    .home-featured-card .portfolio-description span {
        opacity: 1 !important;
        color: #6b7280;
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
    @media (max-width: 991px) {
        .home-featured-carousel .flickity-viewport {
            overflow: hidden;
        }
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
    .home-product-tabs {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.5rem;
    }
    .home-product-tab {
        border: 1px solid #d1d5db;
        background: #fff;
        color: #1f2937;
        border-radius: 999px;
        padding: 0.5rem 1rem;
        font-size: 0.88rem;
        font-weight: 600;
        line-height: 1.2;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .home-product-tab:hover {
        border-color: #1b2838;
        color: #1b2838;
    }
    .home-product-tab.is-active {
        background: #1b2838;
        border-color: #1b2838;
        color: #fff;
    }
    .home-product-card-wrap.is-hidden {
        display: none;
    }
    .home-products-carousel {
        padding-bottom: 2.8rem;
    }
    .home-products-carousel .polo-carousel-item {
        padding-bottom: 10px;
    }
    .home-products-carousel .flickity-page-dots {
        display: none !important;
        bottom: -4px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
        width: max-content;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0;
        margin: 0;
    }
    .home-product-desktop-carousel .flickity-button {
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16);
    }
    .home-product-desktop-carousel .flickity-button:hover {
        background: #1b2838;
    }
    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(15,23,42,0.06);
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        border: 1px solid rgba(39,68,93,0.06);
        height: 100%;
        display: flex;
        flex-direction: column;
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
    .product-card-image {
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
    .home-product-placeholder {
        width: 100%;
        height: 100%;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        background: linear-gradient(135deg, #132235 0%, #22364d 100%);
        color: rgba(255,255,255,0.9);
        text-align: center;
    }
    .home-product-placeholder i {
        font-size: 2.35rem;
        opacity: 0.9;
        animation: saw-blade-spin 11s linear infinite;
    }
    .home-product-placeholder span {
        font-size: 0.78rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.72);
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
        padding: 16px 18px 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 132px;
    }
    .product-card-brand {
        display: block;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #27445d;
        margin-bottom: 8px;
        min-height: 16px;
    }
    .product-card-brand.is-empty {
        visibility: hidden;
    }
    .product-card-title {
        font-size: 16px;
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
    }
    .product-card-link {
        font-size: 13px;
        font-weight: 600;
        color: #27445d;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: auto;
    }
    .home-product-desktop-carousel .flickity-button {
        top: 42%;
        bottom: auto;
        width: 34px;
        height: 34px;
        transform: translateY(-50%);
        z-index: 4;
    }
    .home-product-desktop-carousel .flickity-prev-next-button.previous {
        left: -12px;
    }
    .home-product-desktop-carousel .flickity-prev-next-button.next {
        right: -12px;
    }
    .home-product-desktop-carousel .flickity-page-dots {
        bottom: -2px;
    }
    .home-product-desktop-carousel {
        padding-left: 22px;
        padding-right: 22px;
    }
    .home-products-empty {
        text-align: center;
        color: #6b7280;
        font-size: 0.95rem;
        margin: 0.25rem 0 0;
    }
    .home-products-cta {
        display: flex;
        justify-content: center;
        width: 100%;
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
    @media (max-width: 991px) {
        .home-category-carousel .flickity-viewport {
            overflow: hidden;
        }
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
        isolation: isolate;
    }
    .home-section-divider {
        height: 1.5rem;
        background: linear-gradient(180deg, #e8ecf0 0%, #f1f4f8 100%);
    }
    .home-cta-saw-blades {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }
    .home-cta-saw-blade {
        position: absolute;
        width: var(--sb-size, 48px);
        height: var(--sb-size, 48px);
        left: var(--sb-x);
        top: var(--sb-y);
        transform-origin: center center;
        background: url('{{ asset("assets/images/saw-blade-bg.svg") }}') center/contain no-repeat;
        opacity: 0.22;
        animation: saw-blade-spin-centered var(--sb-dur, 25s) linear infinite;
    }
    .home-cta-content {
        position: relative;
        z-index: 2;
        transform: translateZ(0);
        backface-visibility: hidden;
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
        [data-animate],
        [data-caption-animate],
        .animated {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            animation: none !important;
            transition: none !important;
        }
        .home-cta-saw-blades {
            opacity: 1;
        }
        .home-cta-saw-blade {
            animation: none;
            opacity: 0.16;
        }
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
                        <h1>{{ $isEn ? ($slide['title_en'] ?? $slide['title_tr']) : $slide['title_tr'] }}</h1>
                    @endif
                    @if(!empty($slide['subtitle_tr']))
                        <p class="lead">{{ $isEn ? ($slide['subtitle_en'] ?? $slide['subtitle_tr']) : $slide['subtitle_tr'] }}</p>
                    @endif
                    @if(!empty($slide['button_text_tr']) && !empty($slide['button_link']))
                        <div><a href="{{ $localizeHomeSlideUrl($slide['button_link']) }}" class="btn btn-primary">{{ $isEn ? ($slide['button_text_en'] ?? $slide['button_text_tr']) : $slide['button_text_tr'] }}</a></div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Varsayılan: Tek sabit hero - yüksek kaliteli makine görseli hissi --}}
    <div id="slider" class="home-hero" style="min-height: 72vh; background: url('{{ asset('assets/images/blog/blog-banner.webp') }}') center/cover no-repeat; position: relative;">
        <div style="position: absolute; inset: 0; background: linear-gradient(90deg, rgba(13,27,42,0.88) 0%, rgba(13,27,42,0.5) 100%, transparent 30%);"></div>
        <div class="container" style="position: relative; z-index: 2; padding-top: 140px; padding-bottom: 100px;">
            <div class="slide-captions text-light">
                <h1 style="font-weight: 700; letter-spacing: 0.02em; font-size: 3rem; line-height: 1.2;">{{ __('home.hero_slogan') }}</h1>
                <p class="lead" style="font-size: 1.25rem; max-width: 540px; margin-bottom: 2rem;">{{ __('home.hero_subtitle') }}</p>
                <a href="{{ $productsUrl }}" class="btn btn-primary">{{ __('home.hero_cta') }}</a>
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
                <div class="home-category-slide">
                    <div class="portfolio-item img-zoom home-catalog-card">
                        <div class="portfolio-item-wrap">
                            <div class="portfolio-image">
                                <a href="{{ route($productsIndexRoute, ['slug' => $categorySlug]) }}">
                                    @if($category->image)
                                        <x-webp-image
                                            :src="asset('storage/' . $category->image)"
                                            :alt="$categoryName"
                                            :loading="$index === 0 ? 'eager' : 'lazy'"
                                            @if($index === 0) fetchpriority="high" @endif
                                        />
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-dark text-light home-catalog-placeholder" style="min-height:100%; width:100%;">
                                            <i class="icon-grid" style="font-size:3.5rem; opacity:0.6;"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="portfolio-description">
                                <h3><a href="{{ route($productsIndexRoute, ['slug' => $categorySlug]) }}">{{ $categoryName }}</a></h3>
                                <p class="catalog-desc">
                                    {{ $categoryText ?: __('home.categories_subtitle') }}
                                </p>
                                <div class="catalog-actions">
                                    <a href="{{ route($productsIndexRoute, ['slug' => $categorySlug]) }}" class="btn btn-light btn-outline btn-rounded" aria-label="{{ __('home.detail_button') }}" title="{{ __('home.detail_button') }}">
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
                        'url' => $contactUrl,
                        'icon' => 'fa fa-wrench',
                        'title' => __('home.service_technical'),
                        'desc' => __('home.service_technical_desc'),
                        'button' => __('home.service_call'),
                        'phone_cta' => $phoneHref,
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
                <div class="col-lg-4 m-b-30">
                    @if(!empty($serviceCard['phone_cta']))
                    {{-- Teknik Servis kartı: Kart bilgilendirici, sadece "Ara" butonu telefon linki --}}
                    <div class="icon-box effect medium border text-center home-service-box p-4 text-dark">
                        <div class="icon"><i class="{{ $serviceCard['icon'] }}"></i></div>
                        <h3>{{ $serviceCard['title'] }}</h3>
                        @if(!empty($serviceCard['desc']))
                            <p class="text-muted">{{ $serviceCard['desc'] }}</p>
                        @else
                            <div class="home-service-spacer"></div>
                        @endif
                        <a href="{{ $serviceCard['phone_cta'] }}" class="btn btn-outline-dark btn-rounded home-service-cta text-decoration-none">{{ $serviceCard['button'] }} <i class="icon-chevron-right"></i></a>
                    </div>
                    @else
                    <a href="{{ $serviceCard['url'] }}" class="text-decoration-none text-dark">
                        <div class="icon-box effect medium border text-center home-service-box p-4">
                            <div class="icon"><i class="{{ $serviceCard['icon'] }}"></i></div>
                            <h3>{{ $serviceCard['title'] }}</h3>
                            @if(!empty($serviceCard['desc']))
                                <p class="text-muted">{{ $serviceCard['desc'] }}</p>
                            @else
                                <div class="home-service-spacer"></div>
                            @endif
                            <span class="btn btn-outline-dark btn-rounded home-service-cta">{{ $serviceCard['button'] }} <i class="icon-chevron-right"></i></span>
                        </div>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Product Explorer --}}
    @if($homeProducts->isNotEmpty())
    <section id="home-products-section" class="background-grey p-t-50 p-b-50">
        <div class="container">
            <div class="heading-text heading-section home-heading-agency text-center m-b-30">
                <h2>{{ __('home.product_groups_title') }}</h2>
            </div>

            <div class="home-product-tabs" role="tablist" aria-label="{{ __('home.product_groups_title') }}">
                <button type="button" class="home-product-tab is-active" data-home-product-tab="all">
                    {{ __('home.all_products_tab') }}
                </button>
                @foreach($homeProductCategories as $category)
                <button type="button" class="home-product-tab" data-home-product-tab="{{ $category->id }}">
                    {{ $category->{$categoryNameField} ?: $category->name_tr }}
                </button>
                @endforeach
            </div>

            <div class="carousel home-product-desktop-carousel home-products-carousel d-none d-lg-block"
                 data-items="4"
                 data-items-lg="4"
                 data-items-md="3"
                 data-items-sm="2"
                 data-items-xs="1"
                 data-margin="12"
                 data-loop="false"
                 data-group-cells="true"
                 data-dots="false"
                 data-arrows="true">
                @foreach($homeProducts as $index => $product)
                @php
                    $productName = $product->{$productNameField} ?: $product->name_tr;
                    $imageItems = $product->media ? $product->media->where('media_type', 'image') : collect();
                    $productImages = $imageItems->isNotEmpty()
                        ? $imageItems->map(fn($m) => $resolveMediaUrl($m->path))->filter()->values()
                        : collect();
                    if ($productImages->isEmpty() && $product->thumbnail) {
                        $thumbnailUrl = $resolveMediaUrl($product->thumbnail);
                        if ($thumbnailUrl) {
                            $productImages = collect([$thumbnailUrl]);
                        }
                    }
                    $hasSecondImage = $productImages->count() > 1;
                    $productImage = $productImages->first();
                    $hasProductImage = filled($productImage);
                @endphp
                <div class="m-b-30 home-product-card-wrap" data-home-product-card data-category-id="{{ $product->category_id }}">
                    <article class="product-card">
                        <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}" class="product-card-media">
                            @if($hasProductImage)
                            <img
                                src="{{ $productImage }}"
                                alt="{{ $productName }}"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                class="product-card-image product-card-image-main"
                                onerror="this.onerror=null;this.src='{{ $fallbackProductImage }}';"
                                @if($index === 0) fetchpriority="high" @endif
                            >
                            @if($hasSecondImage)
                            <img
                                src="{{ $productImages->get(1) }}"
                                alt="{{ $productName }}"
                                loading="lazy"
                                class="product-card-image product-card-image-hover"
                                onerror="this.onerror=null;this.src='{{ $fallbackProductImage }}';"
                            >
                            @endif
                            @else
                            <div class="home-product-placeholder">
                                <i class="icon-cpu" aria-hidden="true"></i>
                                <span>{{ __('home.image_placeholder') }}</span>
                            </div>
                            @endif
                            <span class="product-card-overlay">{{ __('home.detail_button') }} <i class="icon-chevron-right"></i></span>
                        </a>
                        <div class="product-card-body">
                            <span class="product-card-brand {{ $product->brand ? '' : 'is-empty' }}">{{ $product->brand?->name ?? '&nbsp;' }}</span>
                            <h3 class="product-card-title">
                                <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}">{{ $productName }}</a>
                            </h3>
                            <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}" class="product-card-link">
                                {{ __('home.detail_button') }} <i class="icon-chevron-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
            <div class="carousel home-product-mobile-carousel home-products-carousel d-lg-none"
                 data-items="2"
                 data-items-sm="2"
                 data-items-xs="1"
                 data-margin="12"
                 data-loop="false"
                 data-group-cells="true"
                 data-dots="false"
                 data-arrows="false">
                @foreach($homeProducts as $index => $product)
                @php
                    $productName = $product->{$productNameField} ?: $product->name_tr;
                    $imageItems = $product->media ? $product->media->where('media_type', 'image') : collect();
                    $productImages = $imageItems->isNotEmpty()
                        ? $imageItems->map(fn($m) => $resolveMediaUrl($m->path))->filter()->values()
                        : collect();
                    if ($productImages->isEmpty() && $product->thumbnail) {
                        $thumbnailUrl = $resolveMediaUrl($product->thumbnail);
                        if ($thumbnailUrl) {
                            $productImages = collect([$thumbnailUrl]);
                        }
                    }
                    $hasSecondImage = $productImages->count() > 1;
                    $productImage = $productImages->first();
                    $hasProductImage = filled($productImage);
                @endphp
                <div class="home-product-card-wrap" data-home-product-card data-category-id="{{ $product->category_id }}">
                    <article class="product-card">
                        <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}" class="product-card-media">
                            @if($hasProductImage)
                            <img
                                src="{{ $productImage }}"
                                alt="{{ $productName }}"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                class="product-card-image product-card-image-main"
                                onerror="this.onerror=null;this.src='{{ $fallbackProductImage }}';"
                            >
                            @if($hasSecondImage)
                            <img
                                src="{{ $productImages->get(1) }}"
                                alt="{{ $productName }}"
                                loading="lazy"
                                class="product-card-image product-card-image-hover"
                                onerror="this.onerror=null;this.src='{{ $fallbackProductImage }}';"
                            >
                            @endif
                            @else
                            <div class="home-product-placeholder">
                                <i class="icon-cpu" aria-hidden="true"></i>
                                <span>{{ __('home.image_placeholder') }}</span>
                            </div>
                            @endif
                            <span class="product-card-overlay">{{ __('home.detail_button') }} <i class="icon-chevron-right"></i></span>
                        </a>
                        <div class="product-card-body">
                            <span class="product-card-brand {{ $product->brand ? '' : 'is-empty' }}">{{ $product->brand?->name ?? '&nbsp;' }}</span>
                            <h3 class="product-card-title">
                                <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}">{{ $productName }}</a>
                            </h3>
                            <a href="{{ route($productsShowRoute, ['slug' => $product->{$productSlugField} ?: $product->slug_tr]) }}" class="product-card-link">
                                {{ __('home.detail_button') }} <i class="icon-chevron-right"></i>
                            </a>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
            <p class="home-products-empty d-none" data-home-products-empty>{{ __('home.no_products') }}</p>

            <div class="home-products-cta m-t-20">
                <a href="{{ $productsUrl }}" class="btn btn-outline btn-dark btn-rounded">{{ __('home.cta_products') }}</a>
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
        <div class="container home-cta-content">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('home-products-section');
    if (!section) return;

    const tabs = section.querySelectorAll('[data-home-product-tab]');
    const desktopCards = section.querySelectorAll('.home-product-desktop-carousel [data-home-product-card]');
    const mobileCards = section.querySelectorAll('.home-product-mobile-carousel [data-home-product-card]');
    const carousels = section.querySelectorAll('.home-products-carousel');
    const emptyState = section.querySelector('[data-home-products-empty]');

    const applyFilterToCards = (cards, categoryId) => {
        let matchingCount = 0;
        let visibleCount = 0;

        cards.forEach((card) => {
            const cardCategory = card.dataset.categoryId || '';
            const isMatchingCategory = categoryId === 'all' || cardCategory === categoryId;
            if (isMatchingCategory) {
                matchingCount++;
            }
            const shouldShow = isMatchingCategory;
            card.classList.toggle('is-hidden', !shouldShow);
            const carouselCell = card.closest('.polo-carousel-item');
            if (carouselCell) {
                carouselCell.style.display = shouldShow ? '' : 'none';
            }
            if (shouldShow) {
                visibleCount++;
            }
        });

        return matchingCount;
    };

    const applyFilter = (categoryId) => {
        const desktopMatchingCount = applyFilterToCards(desktopCards, categoryId);
        applyFilterToCards(mobileCards, categoryId);

        tabs.forEach((tab) => {
            tab.classList.toggle('is-active', tab.dataset.homeProductTab === categoryId);
        });

        if (emptyState) {
            emptyState.classList.toggle('d-none', desktopMatchingCount > 0);
        }

        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.flickity) {
            carousels.forEach((carousel) => {
                const $carousel = window.jQuery(carousel);
                if ($carousel.data('flickity')) {
                    $carousel.flickity('resize');
                    $carousel.flickity('select', 0, false, true);
                }
            });
        }
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', function () {
            applyFilter(this.dataset.homeProductTab || 'all');
        });
    });

    applyFilter('all');
});
</script>
@endpush
