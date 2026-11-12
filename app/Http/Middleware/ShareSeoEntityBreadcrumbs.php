<?php

namespace App\Http\Middleware;

use App\Models\ProductPage;
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
        $locale = App::currentLocale();

        // Strip only the leading locale segment: str_replace() would also cut
        // the locale out of slugs (e.g. "ro" inside "/ro/catalog/profesiinii-...").
        $route = trim(preg_replace('#^' . preg_quote($locale, '#') . '(/|$)#', '', ltrim($request->path(), '/')), '/');

        $urlParts = $route === '' ? [] : explode('/', $route);

        $urlPathesArray = ['/'];
        for ($i = 0; $i < count($urlParts); $i++) {
            $urlPathesArray[] = implode('/', array_slice($urlParts, 0, $i + 1));
        }

        $breadcrumbs = collect($urlPathesArray)->map(function ($item) use ($locale) {
            $seoEntity = SeoEntity::where('slug', 'LIKE', $item)->first();
            if (!$seoEntity) {
                return null;
            }

            return [
                'title' => $seoEntity->breadcrumbs_title ?: $this->entityName($seoEntity),
                'slug'  => $seoEntity->slug === '/'
                    ? '/' . $locale
                    : '/' . $locale . '/' . $seoEntity->slug,
            ];
        })->filter()->values()->toArray();

        // $homePage = SeoEntity::where('slug', 'LIKE', '/')->first();
        // if ($homePage)
        //     array_unshift($breadcrumbs, [
        //         'title' => __('app.home'),
        //         'slug' => '/' . App::currentLocale()
        //     ]);

        Inertia::share('breadcrumbs', $breadcrumbs);
        return $next($request);
    }

    /**
     * Product pages fall back to the product name (as in the H1) instead of
     * the SEO title, which carries "- Купити в Україні"-style suffixes.
     */
    private function entityName(SeoEntity $seoEntity): string
    {
        $entity = $seoEntity->seoEntiteable;
        if ($entity instanceof ProductPage) {
            return $entity->title ?: ($entity->product?->name ?: $seoEntity->title);
        }

        return (string) $seoEntity->title;
    }
}
