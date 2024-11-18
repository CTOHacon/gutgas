<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomePageController extends Controller
{
    /**
     * Display the specified resource.
     */

    public function __construct(private ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show(Request $request, Page $page)
    {
        $requiredProductsIds = [];
        $locale = app()->getLocale();

        $catalogPreviewSections = $page->getMeta('catalogPreviewItems_' . $locale, []);

        foreach ($catalogPreviewSections as $section) {
            $requiredProductsIds = array_merge($requiredProductsIds, $section['products']);
            if ($section['featuredProduct']) $requiredProductsIds[] = +$section['featuredProduct'];
        }
        return Inertia::render('HomePage', [
            'productsCatalogData' => $this->productService->productsCatalogData($request),
            'previewProducts' => Product::query()->whereIn('id', $requiredProductsIds)->get(),
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Page/HomePageEdit', [
            'page' => $page,
        ]);
    }
}
