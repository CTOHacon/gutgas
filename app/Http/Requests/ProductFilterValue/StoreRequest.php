<?php

namespace App\Http\Requests\ProductFilterValue;

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
        return [
            'value' => 'required|string|max:255',
            'sequence' => 'numeric|min:0',
            'product_filter_id' => 'required|integer|exists:product_filters,id',
        ];
    }
}
