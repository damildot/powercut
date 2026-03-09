 <style>
    /* 1. Hizalama */
    .social-icons {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    /* Linklerin temel geçiş ayarı */
    .social-icons ul li a {
        transition: all 0.3s ease !important;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    /* WHATSAPP (Yeşil) */
    .social-icons ul li.social-whatsapp a:hover {
        background-color: #25D366 !important;
        border-color: #25D366 !important;
        color: #ffffff !important;
    }

    /* TELEFON (Mavi) */
    .social-icons ul li.icon-phone-call a:hover {
        background: linear-gradient(180deg, #34C610FF 50%, #54C221FF 100%) !important; /* Kırmızı Gradient */


        color: #ffffff !important;
    }

    /* MAIL (Kırmızı/Gradient) */
    .social-icons ul li.icon-mail a:hover {
        border-color: #1013C6FF !important;
        background: linear-gradient(180deg, #1013C6FF 25%, #218AC2FF 100%) !important; /* Kırmızı Gradient */
        color: #ffffff !important;
    }

    /* INSTAGRAM (Gradient Efect) */
    .social-icons ul li.social-instagram a:hover {
        background: transparent !important;
        background-color: transparent !important;
        border-color: transparent !important;
    }

    .social-icons ul li.social-instagram a::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(45deg, #f09433 0%, #dc2743 50%, #bc1888 100%) !important;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.4s ease !important;
    }

    .social-icons ul li.social-instagram a:hover::before {
        opacity: 1 !important;
    }

    .social-icons ul li.social-instagram a i {
        position: relative;
        z-index: 5;
        transition: color 0.3s ease !important;
    }

    .social-icons ul li.social-instagram a:hover i {
        color: #ffffff !important;
    }
</style>

@php
    $activeLocale = app()->getLocale();
    $isEn = $activeLocale === 'en';

    $trSwitchUrl = url('/iletisim');
    $enSwitchUrl = url('/en/contact');

    $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();

    if (in_array($currentRouteName, ['blog.index', 'blog.en.index'], true)) {
        $trSwitchUrl = route('blog.index');
        $enSwitchUrl = route('blog.en.index');
    } elseif (in_array($currentRouteName, ['blog.category', 'blog.en.category'], true)) {
        $slug = request()->route('slug');
        $category = $currentRouteName === 'blog.en.category'
            ? \App\Models\BlogCategory::where('slug_en', $slug)->first()
            : \App\Models\BlogCategory::where('slug_tr', $slug)->first();

        $trSwitchUrl = route('blog.category', ['slug' => $category?->slug_tr ?? $slug]);
        $enSwitchUrl = route('blog.en.category', ['slug' => $category?->slug_en ?? $slug]);
    } elseif (in_array($currentRouteName, ['blog.tag', 'blog.en.tag'], true)) {
        $tag = request()->route('tag');
        $trSwitchUrl = route('blog.tag', ['tag' => $tag]);
        $enSwitchUrl = route('blog.en.tag', ['tag' => $tag]);
    } elseif (in_array($currentRouteName, ['blog.show', 'blog.en.show'], true)) {
        $slug = request()->route('slug');
        $post = $currentRouteName === 'blog.en.show'
            ? \App\Models\BlogPost::where('slug_en', $slug)->first()
            : \App\Models\BlogPost::where('slug_tr', $slug)->first();

        $trSwitchUrl = route('blog.show', ['slug' => $post?->slug_tr ?? $slug]);
        $enSwitchUrl = route('blog.en.show', ['slug' => $post?->slug_en ?? $slug]);
    } elseif (in_array($currentRouteName, ['about.index', 'about.en.index'], true)) {
        $trSwitchUrl = route('about.index');
        $enSwitchUrl = route('about.en.index');
    } elseif (in_array($currentRouteName, ['home', 'home.en'], true)) {
        $trSwitchUrl = route('home');
        $enSwitchUrl = route('home.en');
    }

    $blogRoutePrefix = $isEn ? 'blog.en.' : 'blog.';
    $blogCatSlugField = $isEn ? 'slug_en' : 'slug_tr';
    $blogCatNameField = $isEn ? 'name_en' : 'name_tr';
    $contactNavUrl = $isEn ? url('/en/contact') : url('/iletisim');
@endphp


<!-- <div id="topbar" class="dark topbar-transparent topbar-fullwidth">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
       
            </div>
            <div class="col-md-6 d-none d-sm-block">
                <div class="social-icons social-icons-colored-hover">
                    <ul>
                  
                            <li class="social-facebook"><a  target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                   

                            <li class="social-whatsapp"><a ><i class="fab fa-whatsapp"></i></a></li>
                   

               
                            <li class="social-instagram"><a ><i class="fab fa-instagram"></i></a></li>
            

                    </ul>
                </div>  
            </div>
        </div>
    </div>
</div> -->



<header id="header" class="dark" data-fullwidth="true" data-transparent="true">
    <div class="header-inner">
        <div class="container">
            <!--Logo-->
            <div id="logo">
                <a href="{{ url('/') }}">
                    @if(!empty($settings->favicon))
                        <span class="logo-default">
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="{{ $settings->site_title ?? 'POWERCUT' }}" style="max-height: 60px;">
                        </span>
                    @else
                        <span class="logo-default">{{ $settings->site_title ?? 'POWERCUT' }}</span>
                    @endif
                    
                    @if(!empty($settings->favicon))
                        <span class="logo-dark">
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="{{ $settings->site_title ?? 'POWERCUT' }}" style="max-height: 60px;">
                        </span>
                    @else
                        <span class="logo-dark">{{ $settings->site_title ?? 'POWERCUT' }}</span>
                    @endif
                </a>
            </div>
            <!--End: Logo-->
            <!-- Search -->
            <!--  <div id="search"><a id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i class="icon-x"></i></a>
                <form class="search-form" action="search-results-page.html" method="get">
                    <input class="form-control" name="q" type="text" placeholder="Type & Search..." />
                    <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                </form>
            </div>-->
            <!-- end: search -->
            <!--Header Extras-->
            <div class="header-extras">
                <ul>
                    <!-- <li>
                        <a id="btn-search" href="#"> <i class="icon-search"></i></a>
                    </li> -->
                    <li>
                        <div class="p-dropdown">
                            <a href="#"><i class="icon-globe"></i><span></span></a>
                            <ul class="p-dropdown-content">
                                <li><a href="{{ $trSwitchUrl }}">Türkçe</a></li>
                                <li><a href="{{ $enSwitchUrl }}">English</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <!--end: Header Extras-->
            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
                <a class="lines-button x"><span class="lines"></span></a>
            </div>
            <!--end: Navigation Resposnive Trigger-->
            <!--Navigation-->
            <div id="mainMenu" class="menu menu-lines">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('nav.home') }}</a></li>
                            <li><a href="{{ $isEn ? route('about.en.index') : route('about.index') }}">{{ __('nav.corporate') }}</a></li>
                            @php
                                $navCategories = $globalCategories ?? collect();
                                $parentCategories = $navCategories->where('parent_id', null);
                            @endphp
                            <li class="dropdown"><a href="{{ url('/urunler') }}">{{ __('nav.products') }}</a>
                                <ul class="dropdown-menu">
                                    @forelse($parentCategories as $parent)
                                        @php $children = $navCategories->where('parent_id', $parent->id); @endphp
                                        <li @if($children->isNotEmpty()) class="dropdown-submenu" @endif>
                                            <a href="{{ url('/urunler/' . $parent->slug_tr) }}">
                                                {{ $parent->name_tr }}
                                                @if($parent->products_count ?? false)
                                                    <span class="badge bg-primary">{{ $parent->products_count }}</span>
                                                @endif
                                            </a>
                                            @if($children->isNotEmpty())
                                                <ul class="dropdown-menu">
                                                    @foreach($children as $child)
                                                        <li>
                                                            <a href="{{ url('/urunler/' . $child->slug_tr) }}">
                                                                {{ $child->name_tr }}
                                                                @if($child->products_count ?? false)
                                                                    <span class="badge bg-primary">{{ $child->products_count }}</span>
                                                                @endif
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @empty
                                        <li><span class="dropdown-item text-muted">Kategori bulunamadı</span></li>
                                    @endforelse
                                    @if(($uncategorizedProductsCount ?? 0) > 0)
                                        <li>
                                            <a href="{{ url('/urunler/' . ($isEn ? 'other' : 'diger')) }}">
                                                {{ __('nav.other') }}
                                                <span class="badge bg-primary">{{ $uncategorizedProductsCount }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="dropdown-divider"></li>
                                    <li><a href="{{ url('/urunler') }}">{{ __('nav.all_products') }}</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="{{ route($blogRoutePrefix . 'index') }}">{{ __('nav.blog') }}</a>
                                <ul class="dropdown-menu">
                                    @if(isset($globalBlogCategories) && $globalBlogCategories->isNotEmpty())
                                        @foreach($globalBlogCategories as $category)
                                        <li>
                                            <a href="{{ route($blogRoutePrefix . 'category', ['slug' => $category->{$blogCatSlugField} ?? $category->slug_tr]) }}">
                                                {{ $category->{$blogCatNameField} ?? $category->name_tr }}
                                            </a>
                                        </li>
                                        @endforeach
                                        <li class="dropdown-divider"></li>
                                    @endif
                                    <li><a href="{{ route($blogRoutePrefix . 'index') }}">{{ __('blog.all_posts') }}</a></li>
                                </ul>
                            </li>
              
                            <li><a href="{{ $contactNavUrl }}">{{ __('nav.contact') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!--end: Navigation-->
        </div>
    </div>
</header>