<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeoEntity\UpdateRequest;
use App\Models\ProductPage;
use App\Models\SeoEntity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeoEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $seoEntity = SeoEntity::findOrFail($id);
        // dd previous route
        return Inertia::render('Admin/SeoEntity/Edit', [
            'seoEntity' => $seoEntity,
            'previousRoute' => url()->previous(),
        ]);
    }

    public function editForProductPage(int $id)
    {
        $seoEntity = SeoEntity::findOrFail($id);
        $productPage = $seoEntity->seoEntiteable;
        $product = $productPage->product;
        return Inertia::render('Admin/SeoEntity/EditForProductPage', [
            'product' => $product->load('seoEntity'),
            'productPage' => $productPage,
            'seoEntity' => $seoEntity,
        ]);
    }

    public function editForPage(int $id)
    {
        $seoEntity = SeoEntity::findOrFail($id);
        $page = $seoEntity->seoEntiteable;
        return Inertia::render('Admin/SeoEntity/EditForPage', [
            'page' => $page,
            'seoEntity' => $seoEntity,
        ]);
    }

    public function editForProductsGroup(int $id)
    {
        $seoEntity = SeoEntity::findOrFail($id);
        $productsGroup = $seoEntity->seoEntiteable;
        return Inertia::render('Admin/SeoEntity/EditForProductsGroup', [
            'productsGroup' => $productsGroup,
            'seoEntity' => $seoEntity,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $data = $request->validated();
        $seoEntity = SeoEntity::findOrFail($id);

        $seoEntity->update($data);

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'SEO entity updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
