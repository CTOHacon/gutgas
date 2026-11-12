<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class UrlLocaleHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $locale = $request->segment(1);

        // Примусова передача поточної мови в УРЛ
        if (!array_key_exists($locale, config('app.locales'))) {
            App::setLocale(config('app.fallback_locale'));
            $defaultLocale = config('app.fallback_locale');

            $newUrl = $defaultLocale . '/' . $request->path();
            // Keep flash data (e.g. analytics_order_id after checkout) alive
            // across this hop; otherwise it is consumed by the 301 request.
            if ($request->hasSession()) {
                $request->session()->reflash();
            }
            return Redirect::to($newUrl, 301);
        } else {
            App::setLocale($locale);
        }

        Inertia::share('locale', app()->getLocale());
        Inertia::share('locales', config('app.locales'));
        Inertia::share('translations', trans('app'));

        return $next($request);
    }
}
