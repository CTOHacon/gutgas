<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequiredProductsGroup\StoreRequest;
use App\Models\Product;
use App\Models\RequiredProductsGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RequiredProductsGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/RequiredProductsGroup/Index', [
            'requiredProductsGroups' => RequiredProductsGroup::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/RequiredProductsGroup/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $requiredProductsGroup = RequiredProductsGroup::create(['name' => $data['name']]);

            $products = $data['products'];

            foreach ($products as $key => $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $requiredProductsGroup->products()->attach($product);
                }
            }
            $requiredProductsGroup->save();

            DB::commit();

            return redirect()->route('admin.requiredProductsGroup.edit', $requiredProductsGroup->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', [
                'type' => 'error',
                'text' => 'Error occurred while creating required products group.',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequiredProductsGroup $requiredProductsGroup)
    {
        return Inertia::render('Admin/RequiredProductsGroup/Edit', [
            'requiredProductsGroup' => $requiredProductsGroup->load('products'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, RequiredProductsGroup $requiredProductsGroup)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $requiredProductsGroup->update(['name' => $data['name']]);

            // Detach all products
            $requiredProductsGroup->products()->sync([]);

            // Attach product and check if it exists
            $products = $data['products'];
            foreach ($products as $key => $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $requiredProductsGroup->products()->attach($product);
                }
            }
            $requiredProductsGroup->save();

            DB::commit();

            return redirect()->route('admin.requiredProductsGroup.edit', $requiredProductsGroup->id)->with('message', [
                'type' => 'success',
                'text' => 'Required products group updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('message', [
                'type' => 'error',
                'text' => 'Error occurred while creating required products group.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequiredProductsGroup $requiredProductsGroup)
    {
        $requiredProductsGroup->delete();

        return redirect()->route('admin.requiredProductsGroup.index');
    }
}
