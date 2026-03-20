<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use App\Models\ContactMessage;
use App\Models\ContactSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(?string $locale = null)
    {
        $locale = $this->normalizeLocale($locale);
        App::setLocale($locale);

        $contactSettings = ContactSetting::first();
        $settings = Setting::first();

        // SEO
        $seoTitleKey = 'seo_title_'.$locale;
        $seoDescKey = 'seo_description_'.$locale;
        $title = $this->t(
            data_get($contactSettings, $seoTitleKey) ?? data_get($settings, $seoTitleKey),
            __('contact.meta_title')
        );
        $description = $this->t(
            data_get($contactSettings, $seoDescKey) ?? data_get($settings, $seoDescKey),
            __('contact.meta_description')
        );

        return view('frontend.contact', [
            'contactSettings' => $contactSettings,
            'settings' => $settings,
            'locale' => $locale,
            'seoTitle' => $title,
            'seoDescription' => $description,
        ]);
    }

    public function store(Request $request, ?string $locale = null)
    {
        $locale = $this->normalizeLocale($locale);
        App::setLocale($locale);

        // Honeypot
        if ($request->filled('website')) {
            return back()->withErrors(['message' => __('Spam tespit edildi.')])->withInput();
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contactMessage = ContactMessage::create([
            'full_name' => $data['full_name'],
            'name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => blank($data['phone'] ?? null) ? null : $data['phone'],
            'subject' => blank($data['subject'] ?? null) ? null : $data['subject'],
            'message' => $data['message'],
            'locale' => $locale,
            'status' => 'new',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $to = config('mail.contact_to')
            ?: ContactSetting::query()->value('email')
            ?: config('mail.from.address');

        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($to)->send(new ContactFormSubmitted($contactMessage));
            } catch (\Throwable $e) {
                Log::error('Contact form mail failed', [
                    'to' => $to,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', __('contact.success'));
    }

    private function normalizeLocale(?string $locale): string
    {
        $fromQuery = request()->get('lang');
        $candidate = $locale ?: $fromQuery;
        return in_array($candidate, ['tr', 'en']) ? $candidate : 'tr';
    }

    private function t(?string $value, string $default = ''): string
    {
        return $value !== null && $value !== '' ? $value : $default;
    }
}
