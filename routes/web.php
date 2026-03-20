<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PageController;

// Cache temizleme - /pc-flush?token=xxx (clear/run-cache WAF tarafından engellenebilir)
// .env: CACHE_TOKEN=gizli_kelimen
Route::get('/pc-flush', function () {
    $token = request('token');
    $expected = config('app.cache_token') ?: env('CACHE_TOKEN', '');
    if (empty($expected)) {
        return response("Hata: .env'e CACHE_TOKEN=xxx ekleyin. Config cache varsa bootstrap/cache/config.php silin.", 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
    if ($token !== $expected) {
        return response('Hata: Geçersiz token', 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return response('Cache temizlendi: view, config, cache, route', 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('clear.cache');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/en', [HomeController::class, 'index'])->name('home.en');

// Static Pages
Route::get('/gizlilik-politikasi', [PageController::class, 'privacyPolicy'])->name('privacy');
Route::get('/en/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.en');
Route::get('/kvkk', [PageController::class, 'kvkk'])->name('kvkk');
Route::get('/en/kvkk', [PageController::class, 'kvkk'])->name('kvkk.en');

// About (Kurumsal)
Route::get('/hakkimizda', [AboutController::class, 'index'])->name('about.index');
Route::get('/en/about', [AboutController::class, 'index'])->name('about.en.index');

// Product Routes
Route::get('/urunler/{slug?}', [ProductController::class, 'index'])->name('products.index');
Route::get('/urun/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/en/products/{slug?}', [ProductController::class, 'index'])->name('products.en.index');
Route::get('/en/product/{slug}', [ProductController::class, 'show'])->name('products.en.show');

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

// Cache oluşturma - /pc-build?token=xxx
Route::get('/pc-build', function () {
    $token = request('token');
    $expected = config('app.cache_token') ?: env('CACHE_TOKEN', '');
    if (empty($expected)) {
        return response("Hata: .env'e CACHE_TOKEN=xxx ekleyin. Config cache varsa bootstrap/cache/config.php silin.", 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
    if ($token !== $expected) {
        return response('Hata: Geçersiz token', 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return response('Cache tamamlandı: config, route, view', 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('run.cache');

// Eski upload dosyalarini public/storage altina tasima - /pc-storage-sync?token=xxx
Route::get('/pc-storage-sync', function () {
    $token = request('token');
    $expected = config('app.cache_token') ?: env('CACHE_TOKEN', '');

    if (empty($expected)) {
        return response("Hata: .env'e CACHE_TOKEN=xxx ekleyin. Config cache varsa bootstrap/cache/config.php silin.", 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    if ($token !== $expected) {
        return response('Hata: Geçersiz token', 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    $legacyRoot = storage_path('app/public');
    $publicRoot = config('filesystems.disks.public.root');

    if (! File::exists($legacyRoot)) {
        return response("Kaynak klasor bulunamadi: {$legacyRoot}", 404)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    if (is_link($publicRoot)) {
        @unlink($publicRoot);
    } elseif (file_exists($publicRoot) && ! is_dir($publicRoot)) {
        return response("Hata: {$publicRoot} mevcut ama klasor degil.", 409)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    File::ensureDirectoryExists($publicRoot);

    try {
        File::copyDirectory($legacyRoot, $publicRoot);
    } catch (\Throwable $e) {
        return response("Hata: dosyalar tasinamadi.\nKaynak: {$legacyRoot}\nHedef: {$publicRoot}\nMesaj: {$e->getMessage()}", 500)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    return response("Storage senkron tamamlandi.\nKaynak: {$legacyRoot}\nHedef: {$publicRoot}\nArtik yeni yuklemeler dogrudan public/storage altina kaydedilecek.", 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('storage.sync');

// Storage path kontrol - /pc-storage-check?token=xxx&path=blog/images/file.webp
Route::get('/pc-storage-check', function () {
    $token = request('token');
    $expected = config('app.cache_token') ?: env('CACHE_TOKEN', '');

    if (empty($expected)) {
        return response("Hata: .env'e CACHE_TOKEN=xxx ekleyin. Config cache varsa bootstrap/cache/config.php silin.", 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    if ($token !== $expected) {
        return response('Hata: Geçersiz token', 403)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    $relativePath = ltrim((string) request('path', ''), '/');

    if ($relativePath === '') {
        return response('Hata: path parametresi gerekli. Ornek: ?token=xxx&path=blog/images/dosya.webp', 422)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    $legacyRoot = storage_path('app/public');
    $publicRoot = config('filesystems.disks.public.root');
    $legacyFile = $legacyRoot . DIRECTORY_SEPARATOR . $relativePath;
    $publicFile = $publicRoot . DIRECTORY_SEPARATOR . $relativePath;

    $lines = [
        'Requested: ' . $relativePath,
        'Configured public disk root: ' . $publicRoot,
        'Legacy root: ' . $legacyRoot,
        'Exists in configured public root: ' . (File::exists($publicFile) ? 'YES' : 'NO'),
        'Exists in legacy root: ' . (File::exists($legacyFile) ? 'YES' : 'NO'),
        'Configured public file: ' . $publicFile,
        'Legacy file: ' . $legacyFile,
    ];

    return response(implode("\n", $lines), 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('storage.check');