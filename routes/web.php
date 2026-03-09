<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PageController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/en', [HomeController::class, 'index'])->name('home.en');

// Static Pages
Route::get('/gizlilik-politikasi', [PageController::class, 'privacyPolicy'])->name('privacy');
Route::get('/en/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.en');
Route::get('/kvkk', [PageController::class, 'kvkk'])->name('kvkk');

// About (Kurumsal)
Route::get('/hakkimizda', [AboutController::class, 'index'])->name('about.index');
Route::get('/en/about', [AboutController::class, 'index'])->name('about.en.index');

// Product Routes
Route::get('/urunler/{slug?}', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('products.show');

// Blog Routes (default TR)
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/kategori/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/etiket/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Blog Routes (EN prefix)
Route::prefix('en/blog')->name('blog.en.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Contact (default + localized)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:contact');

// Turkish friendly slug
Route::get('/iletisim', [ContactController::class, 'index'])->name('contact.index.tr');
Route::post('/iletisim', [ContactController::class, 'store'])
    ->name('contact.store.tr')
    ->middleware('throttle:contact');

Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'tr|en']], function () {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index.locale');
    Route::post('/contact', [ContactController::class, 'store'])
        ->name('contact.store.locale')
        ->middleware('throttle:contact');
});