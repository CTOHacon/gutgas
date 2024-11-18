<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $currentCategory = $this->route('category');
        $id = $currentCategory->id;
        return [
            'name' => 'required|string|max:255',
            'parent_id' => [
                function ($attribute, $value, $fail) use ($id) {
                    if ($id == $value) {
                        $fail('The parent category cannot be the same as the current category.');
                    }
                },
            ],
        ];
    }
}
