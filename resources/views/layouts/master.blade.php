<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="{{ $settings->site_title ?? 'POWERCUT' }}" />
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('meta_description', $settings->seo_description_tr ?? 'Endüstriyel Makina Çözümleri')">
    <meta name="keywords" content="@yield('meta_keywords', 'metal kesim, şerit testere, endüstriyel makina')">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())" />
    
    {{-- Favicon --}}
    @if(!empty($settings->favicon))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @endif
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    @stack('head')
    {{-- Document Title --}}
    <title>@yield('title', $settings->site_title ?? 'POWERCUT')</title>
    
    {{-- Stylesheets (Polo bundle) --}}
    <link href="{{ asset('assets/polo/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/polo/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/polo/css/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/polo/css/custom.css') }}" rel="stylesheet">
    
    {{-- Custom Styles --}}
    <style>
        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            border: 0;
        }

        /* Header Logo Animation */
        #logo a {
            display: inline-block;
            transition: all 0.3s ease;
        }

        #logo a img {
            transition: all 0.3s ease;
            filter: brightness(1);
        }

        /* Hover Effect - Scale & Brightness */
        #logo a:hover {
            transform: scale(1.05);
        }

        #logo a:hover img {
            filter: brightness(1.1);
        }

        /* Scroll Animation - Logo shrinks when scrolling */
        #header.header-sticky #logo a img {
            max-height: 60px !important; /* Scroll'da küçülsün */
            transition: max-height 0.3s ease;
        }

        /* Optional: Subtle pulse animation on page load */
        @keyframes logoPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        #logo a {
            animation: logoPulse 2s ease-in-out;
        }

        /* Floating WhatsApp - icon always white */
        .whatsapp-float {
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #25d366;
            color: #fff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            z-index: 999;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .whatsapp-float:hover,
        .whatsapp-float:focus,
        .whatsapp-float:active {
            color: #fff !important;
        }
        .whatsapp-float i {
            color: #fff !important;
        }
        .whatsapp-float:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.25);
        }

        /* Cookie consent */
        .cookie-consent {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.9);
            color: #fff;
            padding: 12px 16px;
            z-index: 998;
        }
        .cookie-consent .cookie-content {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .cookie-consent p {
            margin: 0;
            font-size: 14px;
        }
        .cookie-consent .cookie-actions .btn {
            margin-left: 6px;
        }
        @media (max-width: 575px) {
            .cookie-consent .cookie-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .cookie-consent .cookie-actions {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Body Inner -->
    <div class="body-inner">
        
        {{-- Header --}}
        @include('layouts.header')
        
        {{-- Main Content --}}
        @yield('content')
        
        {{-- Footer --}}
        @include('layouts.footer')
        
    </div>
    <!-- end: Body Inner -->
    
    {{-- Scroll Top --}}
    <a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>

    {{-- Floating WhatsApp --}}
    @php
        $whatsappNumber = $settings->whatsapp_phone ?? null;
        $whatsappLink = $whatsappNumber
            ? 'https://wa.me/' . preg_replace('/\\D+/', '', $whatsappNumber)
            : null;
    @endphp
    @if($whatsappLink)
    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="whatsapp-float">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif
    
    {{-- Cookie Consent --}}
    <div id="cookie-consent" class="cookie-consent d-none">
        <div class="cookie-content">
            <p>Site deneyimini iyileştirmek için çerez kullanıyoruz. Devam etmeden önce onaylar mısınız?</p>
            <div class="cookie-actions">
                <button class="btn btn-primary btn-sm" id="cookie-accept">Kabul Et</button>
                <a class="btn btn-light btn-sm" href="{{ app()->getLocale() === 'en' ? route('privacy.en') : route('privacy') }}">Detay</a>
            </div>
        </div>
    </div>
    
    {{-- Plugins --}}
    <script src="{{ asset('assets/polo/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/polo/js/plugins.js') }}"></script>
    
    {{-- Template Functions --}}
    <script src="{{ asset('assets/polo/js/functions.js') }}"></script>
    
    {{-- Custom --}}
    <script>
        (function() {
            // Cookie consent & deferred analytics
            const banner = document.getElementById('cookie-consent');
            const acceptBtn = document.getElementById('cookie-accept');
            const CONSENT_KEY = 'cookie_consent';
            const consent = localStorage.getItem(CONSENT_KEY);

            function loadAnalytics() {
                const gaId = "{{ config('services.analytics.id') ?? env('GA_ID') }}";
                if (!gaId) return; // GA disabled
                if (window.__gaLoaded) return;
                window.__gaLoaded = true;
                const s = document.createElement('script');
                s.src = `https://www.googletagmanager.com/gtag/js?id=${gaId}`;
                s.async = true;
                document.head.appendChild(s);
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                window.gtag = gtag;
                gtag('js', new Date());
                gtag('config', gaId, { anonymize_ip: true });
            }

            if (!consent) {
                banner.classList.remove('d-none');
            } else if (consent === 'accepted') {
                loadAnalytics();
            }

            acceptBtn?.addEventListener('click', function() {
                localStorage.setItem(CONSENT_KEY, 'accepted');
                banner.classList.add('d-none');
                loadAnalytics();
            });
        })();
    </script>

    {{-- Custom Scripts --}}
    @stack('scripts')
</body>

</html>

