<?php

namespace App\Http\Requests\Order;

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
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:255',
            'client_email' => 'required|string|regex:/(.+)@(.+)\.(.+)/i|max:255',
            'client_message' => 'nullable|string|max:255',
            'cart_content' => 'required|array',
            'shipping_message' => 'nullable|string|max:255',
            'status_name' => 'nullable|string|max:255',
            'status_color' => 'nullable|string|max:255',
            'compleated' => 'nullable|boolean',
        ];
    }
}
