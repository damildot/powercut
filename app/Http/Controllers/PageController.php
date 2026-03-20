<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function privacyPolicy()
    {
        $locale = request()->segment(1) === 'en' ? 'en' : 'tr';
        App::setLocale($locale);

        return view('frontend.pages.privacy', compact('locale'));
    }

    public function kvkk()
    {
        $locale = request()->segment(1) === 'en' ? 'en' : 'tr';
        App::setLocale($locale);

        return view('frontend.pages.kvkk', compact('locale'));
    }
}
