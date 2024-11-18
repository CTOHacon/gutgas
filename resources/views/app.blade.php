<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('app.locales')[app()->getLocale()]) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="google-site-verification" content="Ul9Uz2ZUtv3wLZlr4YFWO_jKwt8MtdyluHt1vQimpqI">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    @foreach (config('app.locales') as $locale => $localeFullName)
        <link
            href="{{ str_replace(request()->getHttpHost() . '/' . app()->getLocale(), request()->getHttpHost() . '/' . $locale, Request::url()) }}"
            rel="alternate" hreflang="{{ $localeFullName }}" />
    @endforeach
    <link
        href="{{ str_replace(request()->getHttpHost() . '/' . app()->getLocale(), request()->getHttpHost() . '/' . config('app.fallback_locale'), Request::url()) }}"
        rel="alternate" hreflang="x-default" />

    <meta name="robots" content="index, follow">
    @if (Request::is('admin*'))
        @php($__inertiaSsrDispatched = true)
        @php($__inertiaSsrResponse = null)
        <meta name="robots" content="noindex, nofollow">
    @endif

    @routes
    @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])

    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
