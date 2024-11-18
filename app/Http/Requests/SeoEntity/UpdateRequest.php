<?php

namespace App\Http\Requests\SeoEntity;

use App\Models\SeoEntity;
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
        $seoEntityId = '';
        if ($this->seoEntity) {
            $seoEntity = SeoEntity::findOrFail($this->seoEntity);
            $seoEntityId = $seoEntity->id;
        }

        return [
            'title' => ['nullable', 'string', 'max:255'],
            'breadcrumbs_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:seo_entities,slug,' . $seoEntityId],
            'is_active' => ['boolean'],
            'controller' => ['string', 'max:255'],
            'action' => ['string', 'max:255'],
        ];
    }
}
