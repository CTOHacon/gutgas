<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductsGroup;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsCatalogPageController extends Controller
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
        return Inertia::render('ProductsCatalogPage', [
            'page' => $page,
            'productsCatalogData' => $this->productService->productsCatalogData($request)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Page/ProductsCatalogPageEdit', [
            'page' => $page,
        ]);
    }
}
