@props([
    'src' => null,
    'alt' => '',
    'loading' => null,
    'decoding' => null,
    'fetchpriority' => null,
])

@php
    $sourcePath = null;
    $webpPath = null;
    $webpSrc = null;

    if (is_string($src) && $src !== '') {
        $parsedUrl = parse_url($src);
        $sourcePath = $parsedUrl['path'] ?? null;

        if ($sourcePath && preg_match('/\.(jpe?g|png)$/i', $sourcePath)) {
            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $sourcePath);
            $webpDiskPath = public_path(ltrim($webpPath, '/'));

            if (is_file($webpDiskPath)) {
                $query = isset($parsedUrl['query']) ? ('?' . $parsedUrl['query']) : '';
                $webpSrc = asset(ltrim($webpPath, '/')) . $query;
            }
        }
    }
@endphp

@if($webpSrc)
<picture>
    <source srcset="{{ $webpSrc }}" type="image/webp">
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $loading ?? 'lazy' }}"
        decoding="{{ $decoding ?? 'async' }}"
        @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        {{ $attributes }}
    >
</picture>
@else
<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="{{ $loading ?? 'lazy' }}"
    decoding="{{ $decoding ?? 'async' }}"
    @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
    {{ $attributes }}
>
@endif
