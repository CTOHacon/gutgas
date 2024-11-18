<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $productId = '';
        if ($this->product) {
            $productId = $this->product->id;
        }
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'sku' => [
                'required',
                'string',
                'max:255',
                'unique:products,sku' . ',' . $productId,
            ],
            'price' => [
                'required',
                'integer',
                'min:0',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'category_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'attributes' => [
                'nullable',
                'array',
            ],
            'attributes.*' => [
                'integer',
                'exists:attributes,id',
            ],
            'product_filter_values' => [
                'nullable',
                'array',
            ],
            'product_filter_values.*' => [
                'integer',
                'exists:product_filter_values,id',
            ],
            'media_file' => [
                'nullable',
                'array',
            ],
            'sorting_index' => [
                'nullable',
                'integer',
            ],
            'media_file.id' => [
                'nullable',
                'integer',
                'exists:media_files,id',
            ],
            'media_files' => [
                'nullable',
                'array',
            ],
            'media_files.*.id' => [
                'nullable',
                'integer',
                'exists:media_files,id',
            ],

            'products_groups' => [
                'nullable',
                'array',
            ],
            'products_groups.*' => [
                'integer',
                'exists:products_groups,id',
            ],
            'required_products_groups' => [
                'nullable',
                'array',
            ],
            'required_products_groups.*' => [
                'integer',
                'exists:required_products_groups,id',
            ],
        ];
    }
}
