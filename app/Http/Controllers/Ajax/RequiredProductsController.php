<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class RequiredProductsController extends Controller
{
    public function getRequiredProducts(Request $request)
    {
        $data = $request->validate([
            'products' => 'required|array',
            'products.*' => 'required|integer',
        ]);

        $targetProducts = array_map(function ($productId) {
            $product = Product::find($productId);
            if ($product) return $product;
        }, $data['products']);

        $requiredProductsGroups = [];
        // get $requiredProductsGroups from $targetProducts without duplicates
        foreach ($targetProducts as $product) {
            foreach ($product->requiredProductsGroups as $requiredProductsGroup) {
                if (!in_array($requiredProductsGroup, $requiredProductsGroups)) {
                    $requiredProductsGroups[] = $requiredProductsGroup;
                }
            }
        }
        $requiredProducts = [];
        // get $requiredProducts from $requiredProductsGroups without duplicates
        foreach ($requiredProductsGroups as $requiredProductsGroup) {
            foreach ($requiredProductsGroup->products as $requiredProduct) {
                if (!in_array($requiredProduct, $requiredProducts)) {
                    $requiredProducts[] = $requiredProduct;
                }
            }
        }

        return response()->json([
            'requiredProducts' => $requiredProducts,
        ]);
    }
}
