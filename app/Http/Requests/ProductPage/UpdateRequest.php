<?php

namespace App\Http\Requests\ProductPage;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'media_gallery' => ['nullable', 'array'],
            'media_gallery.*.id' => ['integer', 'exists:media_files,id'],
            'characteristic_icons' => ['nullable', 'array'],
            'characteristic_icons.*.id' => ['integer', 'exists:media_files,id'],
            'documentations' => ['nullable', 'array'],
            'documentations.*.id' => ['integer', 'exists:media_files,id'],

            'faqItems' => ['nullable', 'array'],
            'faqItems.*.question' => ['nullable', 'string'],
            'faqItems.*.answer' => ['nullable', 'string'],

            'advantageItems' => ['nullable', 'array'],
            'advantageItems.*.title' => ['nullable', 'string'],
            'advantageItems.*.description' => ['nullable', 'string'],
            'advantageItems.*.image' => ['nullable', 'string'],
        ];
    }
}
