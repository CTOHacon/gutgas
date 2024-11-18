<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPage\UpdateRequest;
use App\Models\Product;
use App\Models\ProductPage;
use Illuminate\Http\Request;

class ProductPageController extends Controller
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
    public function edit(Product $product)
    {
        $productPage = $product->productPage;
        if (!$productPage) {
            $productPage = ProductPage::create([
                'product_id' => $product->id,
            ]);
            return redirect()->route('admin.productPage.edit', $product->id);
        }

        return inertia('Admin/ProductPage/Edit')->with([
            'product' => $product->load('seoEntity'),
            'productPage' => $productPage
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, ProductPage $productPage)
    {
        $data = $request->validated();

        $media_gallery = collect($data['media_gallery'] ?? [])->pluck('id')->toArray();
        $productPage->mediaGallery()->sync($media_gallery);

        $characteristic_icons = collect($data['characteristic_icons'] ?? [])->pluck('id')->toArray();
        $productPage->characteristicIcons()->sync($characteristic_icons);

        $documentations = collect($data['documentations'] ?? [])->pluck('id')->toArray();
        $productPage->documentations()->sync($documentations);

        $productPage->update([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $faqItems = $data['faqItems'] ?? [];
        $productPage->setMeta('faqItems_' . app()->getLocale(), $faqItems);

        $advantageItems = $data['advantageItems'] ?? [];
        $productPage->setMeta('advantageItems_' . app()->getLocale(), $advantageItems);

        $productPage->save();

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Product Page updated successfully',
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
