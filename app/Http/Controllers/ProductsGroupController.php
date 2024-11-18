<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsGroup\StoreRequest;
use App\Models\Page;
use App\Models\ProductsGroup;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsGroupController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAdmin()
    {
        return Inertia::render('Admin/ProductsGroup/Index', [
            'productsGroups' => ProductsGroup::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/ProductsGroup/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $productsGroup = ProductsGroup::create($request->validated());

        $productsGroup->setMetaFromRequest($request);
        return redirect()->route('admin.productsGroup.edit', $productsGroup->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ProductsGroup $productsGroup)
    {
        return Inertia::render('ProductsGroupPage', [
            'productsGroup' => $productsGroup,
            'productsCatalogData' => $this->productService->productsCatalogData(
                $request,
                $this->productService->productsCatalogQuery($request)
                    ->whereHas('productsGroups', function ($query) use ($productsGroup) {
                        $query->where('products_group_id', $productsGroup->id);
                    })
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductsGroup $productsGroup)
    {
        return Inertia::render('Admin/ProductsGroup/Edit', [
            'productsGroup' => $productsGroup,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, ProductsGroup $productsGroup)
    {
        $productsGroup->update($request->validated());

        $productsGroup->setMetaFromRequest($request);

        return redirect()->route('admin.productsGroup.edit', $productsGroup->id)->with('message', [
            'type' => 'success',
            'text' => 'Products group updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductsGroup $productsGroup)
    {
        $productsGroup->delete();

        return redirect()->route('admin.productsGroup.indexAdmin')->with('message', [
            'type' => 'success',
            'text' => 'Products group deleted successfully',
        ]);
    }
}
