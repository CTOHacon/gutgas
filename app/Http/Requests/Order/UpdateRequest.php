<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Admin-side order update. Unlike the public StoreRequest this one may change
 * the status fields, but never the cart snapshot.
 */
class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_name'      => 'required|string|max:255',
            'client_surname'   => 'nullable|string|max:255',
            'client_phone'     => 'required|string|max:255',
            'client_email'     => 'required|string|email|max:255',
            'client_message'   => 'nullable|string|max:255',
            'shipping_message' => 'nullable|string|max:255',
            'status_name'      => 'nullable|string|max:255',
            'status_color'     => 'nullable|string|max:255',
            'compleated'       => 'nullable|boolean',
        ];
    }
}
