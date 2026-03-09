<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $sitemap .= 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // Homepage
        $sitemap .= $this->addUrl(url('/'), now(), 'daily', '1.0');

        // Blog Index
        $sitemap .= $this->addUrl(route('blog.index'), now(), 'daily', '0.9');

        // Blog Categories
        $blogCategories = BlogCategory::active()->get();
        foreach ($blogCategories as $category) {
            $sitemap .= $this->addUrl(
                route('blog.category', $category->slug_tr),
                $category->updated_at,
                'weekly',
                '0.8'
            );
        }

        // Blog Posts
        $blogPosts = BlogPost::active()->published()->get();
        foreach ($blogPosts as $post) {
            $images = $post->image ? [asset('storage/' . $post->image)] : [];
            $sitemap .= $this->addUrl(
                route('blog.show', $post->slug_tr),
                $post->updated_at,
                'monthly',
                '0.7',
                $images
            );
        }

        // Product Categories (if exists)
        if (class_exists(Category::class)) {
            $productCategories = Category::where('is_active', true)->get();
            foreach ($productCategories as $category) {
                // Add category URL here when you have product category routes
                // $sitemap .= $this->addUrl(route('products.category', $category->slug_tr), ...);
            }
        }

        // Products (if exists)
        if (class_exists(Product::class)) {
            $products = Product::where('is_active', true)->get();
            foreach ($products as $product) {
                // Add product URL here when you have product detail routes
                // $sitemap .= $this->addUrl(route('products.show', $product->slug_tr), ...);
            }
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Add URL to sitemap
     */
    private function addUrl(
        string $loc,
        $lastmod = null,
        string $changefreq = 'monthly',
        string $priority = '0.5',
        array $images = []
    ): string {
        $url = '<url>';
        $url .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        
        if ($lastmod) {
            $url .= '<lastmod>' . $lastmod->format('Y-m-d\TH:i:sP') . '</lastmod>';
        }
        
        $url .= '<changefreq>' . $changefreq . '</changefreq>';
        $url .= '<priority>' . $priority . '</priority>';

        // Add images if any
        foreach ($images as $image) {
            $url .= '<image:image>';
            $url .= '<image:loc>' . htmlspecialchars($image) . '</image:loc>';
            $url .= '</image:image>';
        }

        $url .= '</url>';

        return $url;
    }

    /**
     * Generate robots.txt
     */
    public function robots(): Response
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin\n";
        $robots .= "Disallow: /api\n\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }
}

