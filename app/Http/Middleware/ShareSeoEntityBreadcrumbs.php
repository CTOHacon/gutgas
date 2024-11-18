<?php

namespace App\Http\Middleware;

use App\Models\SeoEntity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareSeoEntityBreadcrumbs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = ltrim(str_replace(App::currentLocale(), '', request()->path()), '/');

        $urlParts = explode('/', $route);

        $urlPathesArray = ['/'];
        for ($i = 0; $i < count($urlParts); $i++) {
            $urlPathesArray[] = implode('/', array_slice($urlParts, 0, $i + 1));
        }

        $breadcrumbs = collect($urlPathesArray)->map(function ($item, $key) {
            $seoEntity = SeoEntity::where('slug', 'LIKE', $item)->first();
            if ($seoEntity) {
                return [
                    'title' =>
                        $seoEntity->breadcrumbs_title
                        ? $seoEntity->breadcrumbs_title
                        : $seoEntity->title,
                    'slug' => '/' . App::currentLocale() . '/' . $seoEntity->slug,
                ];
            }
        })->filter(function ($item) {
            return $item !== null;
        })->toArray();

        // $homePage = SeoEntity::where('slug', 'LIKE', '/')->first();
        // if ($homePage)
        //     array_unshift($breadcrumbs, [
        //         'title' => __('app.home'),
        //         'slug' => '/' . App::currentLocale()
        //     ]);

        Inertia::share('breadcrumbs', $breadcrumbs);
        return $next($request);
    }
}
