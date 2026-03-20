<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index()
    {
        // Homepage product explorer (all active products)
        $homeProducts = Product::with(['category', 'brand', 'media'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $homeProductCategoryIds = $homeProducts->pluck('category_id')
            ->filter()
            ->unique()
            ->values();

        $homeProductCategories = Category::query()
            ->where('is_active', true)
            ->whereIn('id', $homeProductCategoryIds)
            ->orderByDesc('show_on_home')
            ->orderBy('sort_order')
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderByDesc('show_on_home')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $homeCategories = $categories->where('show_on_home', true)->values();

        if ($homeCategories->count() < 3) {
            $homeCategories = $categories->values();
        }

        $referenceBrands = Brand::query()
            ->where('is_active', true)
            ->whereNotNull('logo')
            ->orderBy('name')
            ->limit(8)
            ->get();

        // Get Settings (already available via AppServiceProvider but we can use it directly)
        $settings = Setting::first() ?? new Setting();

        // Prepare Slider Data (from settings)
        $sliderData = [];
        if (is_array($settings->hero_slides) && !empty($settings->hero_slides)) {
            $sliderData = $settings->hero_slides;
        }

        // SEO
        $seoTitle = $settings->seo_title_tr ?? $settings->site_title ?? 'POWERCUT - Endüstriyel Makina Çözümleri';
        $seoDescription = $settings->seo_description_tr ?? 'Metal kesim, şerit testere ve endüstriyel makina teknolojileri konusunda uzman çözüm ortağınız.';
        $seoKeywords = 'metal kesim, şerit testere, endüstriyel makina, cnc';

        return view('frontend.home', compact(
            'homeProducts',
            'homeProductCategories',
            'categories',
            'homeCategories',
            'referenceBrands',
            'sliderData',
            'seoTitle',
            'seoDescription',
            'seoKeywords'
        ));
    }
}

