<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null)
    {
        $categories = Category::with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = null;
        $showUncategorized = false;
        if ($categorySlug) {
            $uncategorizedSlug = app()->getLocale() === 'en' ? 'other' : 'diger';
            if ($categorySlug === $uncategorizedSlug) {
                $showUncategorized = true;
            } else {
                $selectedCategory = $categories->firstWhere('slug_tr', $categorySlug)
                    ?? $categories->firstWhere('slug_en', $categorySlug);
            }
        }

        $productsQuery = Product::with(['category', 'brand', 'media'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest();

        if ($showUncategorized) {
            $productsQuery->whereNull('category_id');
        } elseif ($selectedCategory) {
            $categoryIds = $selectedCategory->children->pluck('id')->push($selectedCategory->id);
            $productsQuery->whereIn('category_id', $categoryIds);
        }

        $products = $productsQuery->paginate(12)->withQueryString();

        $newProducts = Product::where('is_new', true)
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'selectedCategory',
            'showUncategorized',
            'newProducts'
        ));
    }

    public function show(string $slug)
    {
        $isEn = app()->getLocale() === 'en';
        $slugField = $isEn ? 'slug_en' : 'slug_tr';

        $product = Product::with([
                'category',
                'brand',
                'media',
                'specifications',
                'documents'
            ])
            ->where(function ($query) use ($slugField, $slug) {
                $query->where($slugField, $slug)
                    ->orWhere('slug_tr', $slug);
            })
            ->where('is_active', true)
            ->firstOrFail();

        $product->increment('views_count');

        $related = $product->category_id
            ? Product::with('media')->where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('is_active', true)->limit(4)->get()
            : Product::with('media')->whereNull('category_id')->where('id', '!=', $product->id)->where('is_active', true)->limit(4)->get();

        return view('frontend.products.show', compact('product', 'related'));
    }
}
