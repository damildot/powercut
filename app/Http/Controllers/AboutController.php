<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class AboutController extends Controller
{
    public function index()
    {
        $locale = request()->segment(1) === 'en' ? 'en' : 'tr';
        App::setLocale($locale);

        $seoTitle = __('about.seo_title');
        $seoDescription = __('about.seo_description');

        return view('frontend.about', compact('locale', 'seoTitle', 'seoDescription'));
    }
}
