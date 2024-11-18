<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilter\StoreRequest;
use App\Models\ProductFilter;
use App\Models\ProductFilterValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductFilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/ProductFilter/Index', [
            'productFilters' => ProductFilter::orderBy('sequence')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $productFilter = ProductFilter::create($data);
            return redirect()->back()->with('message', [
                'type' => 'success',
                'text' => 'Группа фільтрів ' . $productFilter->name . ' успішно створена.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        } finally {
            DB::commit();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, ProductFilter $productFilter)
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $productFilter->update($data);
            return redirect()->back()->with('message', [
                'type' => 'success',
                'text' => 'Группа фільтрів ' . $productFilter->name . ' оновлена.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        } finally {
            DB::commit();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductFilter $productFilter)
    {
        $name = $productFilter->name;
        $productFilter->delete();

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Группа фільтрів ' . $name . ' видалена.',
        ]);
    }
}
