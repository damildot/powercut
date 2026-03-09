@extends('layouts.master')

@section('title', __('privacy.seo_title'))
@section('meta_description', __('privacy.seo_description'))
@section('canonical', $locale === 'en' ? route('privacy.en') : route('privacy'))

@push('head')
    <link rel="alternate" hreflang="tr" href="{{ route('privacy') }}">
    <link rel="alternate" hreflang="en" href="{{ route('privacy.en') }}">
@endpush

@section('content')
    {{-- Page Title --}}
    <section id="page-title" data-bg-parallax="{{ asset('assets/images/blog/blog-banner.png') }}">
        <div class="bg-overlay" style="opacity: 0.75;"></div>
        <div class="container">
            <div class="page-title">
                <h1 class="text-uppercase text-light">{{ __('privacy.title') }}</h1>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="{{ url('/') }}" class="text-light">{{ __('privacy.breadcrumb_home') }}</a></li>
                    <li class="active"><a href="#" class="text-light">{{ __('privacy.breadcrumb_page') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="shape-divider" data-style="1"></div>
    </section>

    {{-- Content --}}
    <section class="background-grey p-t-80 p-b-80">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-10 col-xl-8 mx-auto">
                    <p class="text-muted small mb-4">{{ __('privacy.last_updated') }}: {{ now()->format('d.m.Y') }}</p>
                    <p class="lead mb-5">{{ __('privacy.intro') }}</p>

                    @foreach(__('privacy.sections') as $section)
                        <div class="mb-5">
                            <h3 class="mb-3" style="color: #1a1a1a; font-size: 1.25rem;">{{ $section['title'] }}</h3>
                            <p style="color: #444; line-height: 1.8;">{{ $section['content'] }}</p>
                        </div>
                    @endforeach

                    <div class="mt-5 pt-4">
                        <a href="{{ $locale === 'en' ? url('/en/contact') : url('/iletisim') }}" class="btn btn-primary">
                            {{ __('nav.contact') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
