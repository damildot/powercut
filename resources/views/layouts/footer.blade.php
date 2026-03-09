<!-- Footer -->

<style>
/* Footer social icons fix */
#footer .social-icons ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: flex-start !important;
  gap: 10px;
  flex-wrap: wrap;
  max-width: 100%;
}
#footer .social-icons {
  text-align: left !important;
  justify-content: flex-start !important;
}

/* Footer responsive - mobil uyumluluk */
@media (max-width: 767px) {
  #footer .footer-content {
    padding: 40px 0 30px 0;
  }
  #footer .footer-content .row > [class*="col-"] {
    margin-bottom: 2rem;
  }
  #footer .footer-content .row > [class*="col-"]:last-child {
    margin-bottom: 0;
  }
  #footer .footer-logo {
    max-height: 100px !important;
    max-width: 200px !important;
    margin-right: 0 !important;
    border-right: none !important;
    padding-right: 0 !important;
  }
  #footer .map-container {
    height: 200px !important;
    min-height: 200px !important;
  }
  #footer .copyright-content {
    min-height: auto;
    padding: 20px 15px;
    text-align: center !important;
  }
  #footer .copyright-content .text-end {
    text-align: center !important;
  }
  #footer .copyright-text {
    font-size: 12px;
    line-height: 1.5;
  }
  #footer .widget h4 {
    margin-bottom: 15px;
  }
  #footer .widget p {
    font-size: 13px;
    line-height: 1.6;
  }
  #footer .widget p a {
    word-break: break-all;
  }
  #footer .widget ul.list li a {
    display: inline-block;
    padding: 4px 0;
  }
}
@media (max-width: 575px) {
  #footer .footer-content {
    padding: 30px 0 25px 0;
  }
  #footer .map-container {
    height: 180px !important;
  }
}
</style>

@php
    $isEn = app()->getLocale() === 'en';
    $footerDescription = $isEn
        ? ($settings->seo_description_en ?? __('footer.default_description'))
        : ($settings->seo_description_tr ?? __('footer.default_description'));
@endphp


<footer id="footer" class="inverted">
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <!-- Logo & Description -->
                <div class="col-12 col-md-6 col-xl-3 col-lg-3">
                    <div class="widget">
                        @if(!empty($settings->logo_light))
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('storage/' . $settings->logo_light) }}" 
                                     alt="{{ $settings->site_title ?? 'POWERCUT' }}" 
                                     class="footer-logo" 
                                     style="max-height: 150px; max-width: 250px; width: auto; margin-bottom: 25px; display: block;">
                            </a>
                        @else
                            <h4 class="text-white mb-3">{{ $settings->site_title ?? 'POWERCUT' }}</h4>
                        @endif
                        <p class="mb-4" style="color: rgba(255,255,255,0.7);">
                            {{ $footerDescription }}
                        </p>
                  </div>
                     <!-- Social Icons -->
                        <div class="mb-4 social-icons social-icons-medium social-icons-colored-hover">
                            <ul>
                                @if(!empty($settings->facebook))
                                <li class="social-facebook"><a href="{{ $settings->facebook }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>
                                @endif
                                
                                @if(!empty($settings->instagram))
                                <li class="social-instagram"><a href="{{ $settings->instagram }}" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a></li>
                                @endif
    
                                @if(!empty($settings->whatsapp_phone))
                                <li class="social-whatsapp"><a href="https://wa.me/{{ $settings->whatsapp_phone }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a></li>
                                @endif
    
                              {{--  @if(!empty($settings->email))
                                <li class="social-email"><a href="mailto:{{ $settings->email }}" target="_blank" rel="noopener"><i class="icon-mail"></i></a></li>
                                @endif  --}}
    
                                @if(!empty($settings->google_maps_embed))
                                <li class="social-google"><a href="{{ $settings->google_maps_embed }}" target="_blank" rel="noopener"><i class="fab fa-google"></i></a></li>
                                @endif
                                
                            </ul>
                        </div>
                    </div>
                

                <!-- Corporate -->
                <div class="col-12 col-md-6 col-xl-3 col-lg-3">
                    <div class="widget">
                        <h4>{{ __('footer.corporate_title') }}</h4>
                        <ul class="list">
                          <!-- <li><a href="{{ url('/') }}">Anasayfa</a></li> -->
                            <li><a href="{{ $isEn ? route('about.en.index') : route('about.index') }}">{{ __('footer.about') }}</a></li>
                            <li><a href="{{ $isEn ? route('privacy.en') : route('privacy') }}">{{ __('footer.privacy_policy') }}</a></li>
                            <li><a href="{{ url('/kvkk') }}">KVKK</a></li>
                            <li><a href="{{ $isEn ? route('blog.en.index') : route('blog.index') }}">{{ __('footer.blog') }}</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-12 col-md-6 col-xl-3 col-lg-3">
                    <div class="widget">
                        <h4>{{ __('footer.contact_title') }}</h4>
                        @if($settings->phone)
                        <p class="mb-3">
                            <i class="icon-phone"></i> 
                            <strong>{{ __('footer.phone') }}:</strong><br>
                            <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a>
                        </p>
                        @endif
                        
                        @if($settings->email)
                        <p class="mb-3">
                            <i class="fa fa-envelope"></i> 
                            <strong>{{ __('footer.email') }}:</strong><br>
                            <a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a>
                        </p>
                        @endif

                        @if($settings->address)
                        <p class="mb-3">
                            <i class="fa fa-location-arrow"></i> 
                            <strong>{{ __('footer.address') }}:</strong><br>
                            <a href="https://www.google.com/maps/place/POWERCUT+MAK%C4%B0NA/@39.9958196,32.7484084,896m/data=!3m2!1e3!4b1!4m6!3m5!1s0x14d34be8c216a39d:0x5e40930e8d2592fa!8m2!3d39.9958196!4d32.7484084!16s%2Fg%2F11pvcfx8k9?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" rel="noopener">{{ $settings->address }}</a>
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="col-12 col-md-6 col-xl-3 col-lg-3">
                    @if(!empty($settings->google_maps_embed))
                    <div class="widget">
                       <!-- <h4>KONUM</h4> -->
                        <div class="map-container" style="position: relative; width: 100%; height: 250px; overflow: hidden; border-radius: 8px; margin-bottom: 15px;">
                            {!! $settings->google_maps_embed !!}
                        </div>
                       
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="copyright-content">
        <div class="container">
            <div class="row">
                    <!-- Social icons -->
                  
                <div class="col-12 col-lg-12 text-center text-lg-end">
                    <div class="copyright-text" style="color: rgba(255,255,255,0.7);">
                        &copy; {{ date('Y') }} {{ $settings->site_title ?? 'POWERCUT' }}. {{ __('footer.rights_reserved') }}
                    </div>
            </div>
        </div>
    </div>
</footer>
<!-- end: Footer -->
