@extends('layouts.master')

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $locale === 'en' ? route('about.en.index') : route('about.index'))

@push('head')
    <link rel="alternate" hreflang="tr" href="{{ route('about.index') }}">
    <link rel="alternate" hreflang="en" href="{{ route('about.en.index') }}">
@endpush

@push('styles')
    <style>
        /* Services section - home-agency style (icon-box effect small clean) */
        .about-services-section {
            background: linear-gradient(135deg, #0d1b2a 0%, #1b2838 50%, #0d0d0d 100%);
            position: relative;
        }
        .about-services-section .bg-overlay {
            background: rgba(0, 0, 0, 0.4);
        }
        .about-services-section .icon-box.effect.small.clean .icon i,
        .about-services-section .icon-box.effect.small.clean .icon a {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .about-services-section .icon-box.effect.small.clean h3 {
            color: #ffffff !important;
        }
        .about-services-section .icon-box.effect.small.clean p {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        .about-services-section .icon-box.effect.small.clean .icon i:hover,
        .about-services-section .icon-box.effect.small.clean:hover .icon i {
            color: #FFA500 !important;
        }

        /* Mission & Vision - Polo style cards */
        .about-mv-section {
            background: linear-gradient(180deg, #f8f9fa 0%, #fff 50%, #f8f9fa 100%);
        }
        .about-mv-card {
            background: #fff;
            border-radius: 8px;
            padding: 2.5rem 2rem;
            height: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.06);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }
        .about-mv-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, #e65100, #FFA500);
            transition: height 0.35s ease;
        }
        .about-mv-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }
        .about-mv-card:hover::before {
            height: 100%;
        }
        .about-mv-card .about-mv-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d1b2a 0%, #1e3a5f 50%, #1b2838 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .about-mv-card .about-mv-icon i {
            font-size: 1.75rem;
            color: #fff;
        }
        .about-mv-card h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 0.02em;
        }
        .about-mv-card .lead {
            font-size: 1.05rem;
            line-height: 1.75;
            color: #555;
        }
    </style>
@endpush

@section('content')
    {{-- Page Title / Hero --}}
    <section id="page-title" data-bg-parallax="{{ asset('assets/images/blog/blog-banner.png') }}">
        <div class="bg-overlay" style="opacity: 0.75;"></div>
        <div class="container">
            <div class="page-title">
                <h1 class="text-uppercase text-light">{{ __('nav.corporate') }}</h1>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="{{ url('/') }}" class="text-light">{{ __('about.breadcrumb_home') }}</a></li>
                    <li class="active"><a href="#" class="text-light">{{ __('nav.corporate') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="shape-divider" data-style="1"></div>
    </section>

    {{-- Şirketimiz - Yan yana, Mission & Vision ile aynı stil --}}
    <section class="background-grey p-t-80 p-b-80">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-3 mb-4 mb-lg-0">
                    <div class="heading-text heading-section">
                        <h2>{{ __('about.company_title') }}</h2>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-6">
                            <p class="lead">{{ __('about.intro_1') }}</p>
                        </div>
                        <div class="col-lg-6">
                            <p class="lead">{{ __('about.intro_2') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services - home-agency style (icon-box effect small clean) --}}
    <section class="about-services-section text-light p-t-80 p-b-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="heading-text heading-section">
                        <h1 class="text-medium">{{ __('about.why_title') }}</h1>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-5">
                            <div class="icon-box effect small clean">
                                <div class="icon">
                                    <a href="#"><i class="fa fa-cog"></i></a>
                                </div>
                                <h3>{{ __('about.why_1_title') }}</h3>
                                <p>{{ __('about.why_1_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4 mb-lg-5">
                            <div class="icon-box effect small clean">
                                <div class="icon">
                                    <a href="#"><i class="fa fa-puzzle-piece"></i></a>
                                </div>
                                <h3>{{ __('about.why_2_title') }}</h3>
                                <p>{{ __('about.why_2_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="icon-box effect small clean">
                                <div class="icon">
                                    <a href="#"><i class="fa fa-headset"></i></a>
                                </div>
                                <h3>{{ __('about.why_3_title') }}</h3>
                                <p>{{ __('about.why_3_text') }}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="icon-box effect small clean">
                                <div class="icon">
                                    <a href="#"><i class="fa fa-leaf"></i></a>
                                </div>
                                <h3>{{ __('about.why_4_title') }}</h3>
                                <p>{{ __('about.why_4_text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Vision - Polo style cards --}}
    <section class="about-mv-section p-t-80 p-b-80">
        <div class="container">
            <div class="heading-text heading-section text-center m-b-60">
                <h2>{{ __('about.mv_heading') }}</h2>
                <span class="lead text-muted">{{ __('about.mv_subtitle') }}</span>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-mv-card">
                        <div class="about-mv-icon">
                            <i class="fa fa-bullseye"></i>
                        </div>
                        <h3 class="text-center">{{ __('about.mission_title') }}</h3>
                        <p class="lead text-center mb-0">{{ __('about.mission_text') }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-mv-card">
                        <div class="about-mv-icon">
                            <i class="fa fa-eye"></i>
                        </div>
                        <h3 class="text-center">{{ __('about.vision_title') }}</h3>
                        <p class="lead text-center mb-0">{{ __('about.vision_text') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
