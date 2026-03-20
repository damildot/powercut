@extends('layouts.master')

@section('title', __('kvkk.seo_title'))
@section('meta_description', __('kvkk.seo_description'))
@section('canonical', ($locale ?? app()->getLocale()) === 'en' ? route('kvkk.en') : route('kvkk'))

@php
    $isEn = ($locale ?? app()->getLocale()) === 'en';
    $homeUrl = $isEn ? route('home.en') : route('home');
    $contactUrl = $isEn ? route('contact.index.locale', ['locale' => 'en']) : route('contact.index.tr');
@endphp

@section('content')
    {{-- Page Title --}}
    <section id="page-title" data-bg-parallax="{{ asset('assets/images/blog/blog-banner.png') }}">
        <div class="bg-overlay" style="opacity: 0.75;"></div>
        <div class="container">
            <div class="page-title">
                <h1 class="text-uppercase text-light">{{ __('kvkk.title') }}</h1>
            </div>
            <div class="breadcrumb">
                <ul>
                    <li><a href="{{ $homeUrl }}" class="text-light">{{ __('kvkk.breadcrumb_home') }}</a></li>
                    <li class="active"><a href="#" class="text-light">{{ __('kvkk.breadcrumb_page') }}</a></li>
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
                    <p class="lead mb-5">{{ __('kvkk.intro') }}</p>

                    @foreach(__('kvkk.sections') as $section)
                        <div class="mb-5">
                            <h3 class="mb-3" style="color: #1a1a1a; font-size: 1.25rem;">{{ $section['title'] }}</h3>
                            <p style="color: #444; line-height: 1.8;">{{ $section['content'] }}</p>
                        </div>
                    @endforeach

                    <div class="mt-5 pt-4">
                        <a href="{{ $contactUrl }}" class="btn btn-primary">
                            {{ __('nav.contact') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
