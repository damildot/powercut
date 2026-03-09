<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // View sınıfını import ettik
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;
use App\Models\Setting; // Ayarlar Modelini import ettik
use App\Models\Category; // Kategori Modelini import ettik
use App\Models\BlogCategory; // Blog Kategorilerini import ettik
use App\Models\Product; // Ürün sayısı için
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Locale from path prefix (/en/...), default tr
        $prefix = request()->segment(1);
        $locale = $prefix === 'en' ? 'en' : 'tr';
        App::setLocale($locale);

        // Yıldız (*) karakteri "Tüm view dosyaları" anlamına gelir.
        // Yani header, footer, home fark etmeksizin bu veriler her yere gider.
        View::composer('*', function ($view) {
            
            // 1. Ayarları Çek
            // first() veritabanındaki ilk kaydı alır. 
            // ?? new Setting() kısmı ise; eğer tablo boşsa hata vermesin, boş bir nesne oluştursun demektir.
            $view->with('settings', Setting::first() ?? new Setting());
            $view->with('currentLocale', App::getLocale());

            // 2. Ürün Kategorilerini Çek (Menü için)
            // Sadece aktif olanları alıyoruz
            $view->with('globalCategories', Category::with(['children' => function($q) {
                    $q->where('is_active', true)
                        ->orderBy('sort_order')
                        ->withCount('products');
                }])
                ->withCount('products')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get());

            // 3. Blog Kategorilerini Çek (Blog Menüsü için)
            $view->with('globalBlogCategories', BlogCategory::where('is_active', true)->orderBy('sort_order')->get());

            // 4. Kategorisiz ürün sayısı (Header "Diğer" için)
            $view->with('uncategorizedProductsCount', Product::where('is_active', true)->whereNull('category_id')->count());
        });

        // Contact form rate limit
        RateLimiter::for('contact', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}