<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('app.locales')[app()->getLocale()]) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php($__trackingEnabled = app()->isProduction() && !Request::is('admin*'))

    @if ($__trackingEnabled)
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WC24J939');</script>
        <!-- End Google Tag Manager -->
    @endif

    @production
        <meta name="google-site-verification" content="Ul9Uz2ZUtv3wLZlr4YFWO_jKwt8MtdyluHt1vQimpqI">
        <meta name="google-site-verification" content="hF7a9l4sH80YQ4i6FkfPqMQU9yCSEGEbF4vtclv55lM">
    @endproduction
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
    @if ($__trackingEnabled)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WC24J939"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif
    @inertia
</body>

</html>
