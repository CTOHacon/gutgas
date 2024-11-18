<?php

namespace App\Http\Requests\ProductsGroup;

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
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'icon' => ['required', 'string', 'max:255'],
            'icon_active' => ['nullable', 'string', 'max:255'],
            'icon_hover' => ['nullable', 'string', 'max:255'],
        ];
    }
}
