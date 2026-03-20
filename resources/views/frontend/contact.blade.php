@extends('layouts.master')

@section('title', $seoTitle ?? __('contact.meta_title'))
@section('meta_description', $seoDescription ?? __('contact.meta_description'))

@php
    $locale = $locale ?? 'tr';
    $localeParam = $locale === 'tr' ? [] : ['locale' => $locale];
    $homeUrl = $locale === 'en' ? route('home.en') : route('home');
    $cs = $contactSettings ?? null;
    $csPhone = data_get($cs, 'phone');
    $csEmail = data_get($cs, 'email');
    $addr = data_get($cs, "address_{$locale}") ?? data_get($cs, 'address_tr') ?? $settings->address ?? '';
    $hours = data_get($cs, "working_hours_{$locale}") ?? data_get($cs, 'working_hours_tr') ?? '';
    $waNumber = data_get($cs, 'whatsapp_number') ?? $settings->whatsapp_phone ?? null;
    $waMessage = data_get($cs, "whatsapp_default_message_{$locale}") ?? data_get($cs, 'whatsapp_default_message_tr') ?? __('contact.whatsapp_default_message');
    $map = data_get($cs, 'map_embed_url') ?? $settings->google_maps_embed ?? '';
    $social = data_get($cs, 'social_links') ?? [
        'facebook' => $settings->facebook ?? null,
        'instagram' => $settings->instagram ?? null,
        'linkedin' => $settings->linkedin ?? null,
        'youtube' => $settings->youtube ?? null,
    ];
    $canonicalTr = url('/iletisim');
    $canonicalEn = url('/en/contact');

    // Form action: stay on current slug for clarity
    $formAction = $locale === 'en'
        ? route('contact.store.locale', ['locale' => 'en'])
        : route('contact.store.tr');
@endphp

@push('head')
    <link rel="canonical" href="{{ $locale === 'en' ? $canonicalEn : $canonicalTr }}">
    <link rel="alternate" hreflang="tr" href="{{ $canonicalTr }}">
    <link rel="alternate" hreflang="en" href="{{ $canonicalEn }}">
@endpush

@push('styles')
<style>
    #page-title.contact-page-title {
        background-image: url('{{ asset('assets/polo/images/parallax/contact-banner.png') }}');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-color: #0b0b0b;
        min-height: clamp(240px, 30vw, 420px);
    }
    @media (max-width: 768px) {
        #page-title.contact-page-title {
            background-size: contain;
            min-height: 230px;
        }
    }

    /* Contact page - Proje temasına uyumlu modern tasarım */
    .contact-section {
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
        padding: 4rem 0;
    }
    /* WhatsApp butonu - modern */
    .btn-whatsapp {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%) !important;
        border: none !important;
        color: #fff !important;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);
        transition: all 0.3s ease;
    }
    .btn-whatsapp:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
        color: #fff !important;
    }

    /* Form kartı */
    .contact-form-card {
        background: #fff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    }
    .contact-form-card .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .contact-form-card .form-control:focus {
        border-color: #FFA500;
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.15);
        outline: none;
    }
    .contact-form-card label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    /* Gönder butonu - turuncu gradient */
    .contact-form .btn-primary {
        background: linear-gradient(135deg, #FFA500 0%, #ff8c00 100%) !important;
        border: none !important;
        color: #fff !important;
        font-weight: 600;
        padding: 14px 32px;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(255, 165, 0, 0.35);
        transition: all 0.3s ease;
    }
    .contact-form .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 165, 0, 0.45);
        color: #fff !important;
    }

</style>
@endpush

@section('content')
<!-- Page title -->
<section id="page-title" class="text-light contact-page-title">
    <div class="bg-overlay" style="opacity: 0.75;"></div>
    <div class="container">
        <div class="page-title">
            <h1>{{ __('contact.title') }}</h1>
            <span>{{ __('contact.subtitle') }}</span>
        </div>
        <div class="breadcrumb">
            <ul>
                <li><a href="{{ $homeUrl }}">{{ __('contact.breadcrumb_home') }}</a></li>
                <li class="active"><a href="#">{{ __('contact.breadcrumb_contact') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="shape-divider" data-style="1"></div>
</section>

<!-- Contact -->
<section class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h4>{{ __('contact.info_title') }}</h4>
                    <div class="m-b-20">
                        @if($csPhone)
                            <p><strong>{{ __('contact.phone') }}:</strong> <a href="tel:{{ preg_replace('/\\D+/', '', $csPhone) }}">{{ $csPhone }}</a></p>
                        @endif
                        @if($csEmail)
                            <p><strong>{{ __('contact.email') }}:</strong> <a href="mailto:{{ $csEmail }}">{{ $csEmail }}</a></p>
                        @endif
                        @if($addr)
                            <p><strong>{{ __('contact.address') }}:</strong><br>{!! nl2br(e($addr)) !!}</p>
                        @endif
                        @if($hours)
                            <p><strong>{{ __('contact.hours') }}:</strong><br>{{ $hours }}</p>
                        @endif
                    </div>

                    @if(!empty(array_filter($social)))
                        <div class="social-icons social-icons-colored social-icons-rounded social-icons-rectangle">
                            <ul>
                                @foreach($social as $network => $link)
                                    @if($link)
                                    <li class="social-{{ strtolower($network) }}"><a href="{{ $link }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"><i class="fab fa-{{ strtolower($network) }}"></i></a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($waNumber)
                        <div class="m-t-20">
                            <a class="btn btn-primary btn-block btn-whatsapp" target="_blank" rel="noopener"
                               href="https://wa.me/{{ preg_replace('/\\D+/', '', $waNumber) }}?text={{ urlencode($waMessage) }}">
                                <i class="fab fa-whatsapp"></i> {{ __('contact.whatsapp') }}
                            </a>
                        </div>
                    @endif
            </div>

            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="m-b-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="contact-form-card">
                <form class="contact-form form-transparent" method="POST" action="{{ $formAction }}">
                    @csrf
                    <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="full_name">{{ __('contact.form_name') }}</label>
                            <input type="text" name="full_name" id="full_name" class="form-control name" value="{{ old('full_name') }}" required autocomplete="name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('contact.form_email') }}</label>
                            <input type="email" name="email" id="email" class="form-control email" value="{{ old('email') }}" required autocomplete="email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">{{ __('contact.form_phone') }}</label>
                            <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" autocomplete="tel">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="subject">{{ __('contact.form_subject') }}</label>
                            <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">{{ __('contact.form_message') }}</label>
                        <textarea name="message" id="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="icon-check"></i> {{ __('contact.form_send') }}</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if($map)
<section class="p-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="mapouter" style="height:420px;">
                    {!! $map !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
