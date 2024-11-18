<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFilterValue\StoreRequest;
use App\Http\Requests\ProductFilterValue\UpdateRequest;
use App\Models\ProductFilterValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductFilterValueController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();
        try {
            $productFilter = ProductFilterValue::create($data);
            return redirect()->back()->with('message', [
                'type' => 'success',
                'text' => 'Значення ' . $productFilter->name . ' успішно створено.',
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
    public function update(UpdateRequest $request, ProductFilterValue $productFilterValue)
    {
        DB::beginTransaction();
        $data = $request->validated();

        try {
            $productFilterValue->update($data);
            return redirect()->back()->with('message', [
                'type' => 'success',
                'text' => 'Группа фільтрів ' . $productFilterValue->name . ' оновлена.',
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
    public function destroy(ProductFilterValue $productFilterValue)
    {
        $name = $productFilterValue->name;
        $productFilterValue->delete();

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Группа фільтрів ' . $name . ' видалена.',
        ]);
    }
}
