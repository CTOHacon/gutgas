<?php

namespace App\Services;

use App\Models\AttributeGroup;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\ProductsGroup;
use Illuminate\Http\Request;

class ProductService
{
    public function productsCatalogQuery(Request $request)
    {
        $products = Product::query()->orderBy('sorting_index', 'desc')->orderBy('id', 'desc');

        $priceRange = $request->query('priceRange');

        $products->whereHas('seoEntity', function ($query) {
            $query->where('is_active->' . app()->getLocale(), '=', true);
        });

        // price range filter
        if (!empty ($priceRange)) {
            $products->whereBetween('price', [$priceRange['from'], $priceRange['to']]);
        }

        // product filters
        $productFilterValues = $request->query('selectedProductFilterValues');
        if (!empty ($productFilterValues)) {
            $products->whereHas('productFilterValues', function ($query) use ($productFilterValues) {
                $query->whereIn('product_filter_value_id', $productFilterValues);
            })->get();
        }

        // filter by products group
        $productsGroup = $request->query('productsGroup');
        if (!empty ($productsGroup)) {
            $products->whereHas('productsGroups', function ($query) use ($productsGroup) {
                $query->where('products_groups.id', $productsGroup);
            });
        }

        return $products;
    }

    public function productsCatalogData(Request $request, $productsQuery = null)
    {
        if (!$productsQuery)
            $productsQuery = $this->productsCatalogQuery($request);

        $priceMin = Product::min('price');
        $priceMax = Product::max('price');

        return [
            'priceMin' => +$priceMin,
            'priceMax' => +$priceMax,
            'priceRange' => $request->query('priceRange') ?? [
                'from' => +$priceMin,
                'to' => +$priceMax,
            ],
            'products' => $productsQuery->frontendPaginate(12),

            'productFilters' => ProductFilter::orderBy('sequence')->get(),
            'selectedProductFilterValues' => $request->query('selectedProductFilterValues') ?? [],

            'productsGroup' => $request->query('productsGroup') ?? null,
            'productsGroups' => ProductsGroup::all()->where('is_active', '=', 1),
            'totalProductsCount' => Product::count(),
        ];
    }

    public function extractProductFieldsData(Request $request)
    {
        $productData = $request->only([
            'category_id',
            'sku',
            'name',
            'price',
            'stock',
            'sorting_index',
            'description'
        ]);

        return $productData;
    }
}
