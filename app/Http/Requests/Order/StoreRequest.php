<?php

namespace App\Http\Requests\Order;

use App\Support\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreRequest extends FormRequest
{
    public const SHIPPING_METHODS = ['np_department', 'np_postal_machine', 'np_courier', 'other'];

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
     * Status fields are intentionally absent: they are set server-side.
     * Only product ids and quantities are taken from the cart; everything
     * else (price, name, sku...) is rebuilt from the database.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_name'            => 'required|string|max:255',
            'client_surname'         => 'required|string|max:255',
            'client_phone'           => ['required', 'string', 'max:255', $this->phoneRule()],
            'client_email'           => 'required|string|email|max:255',
            'client_message'         => 'nullable|string|max:255',
            'locale'                 => 'nullable|string|in:' . implode(',', array_keys(config('app.locales'))),

            'cart_content'            => 'required|array|min:1|max:100',
            'cart_content.*.id'       => 'required|integer|exists:products,id',
            'cart_content.*.quantity' => 'required|integer|min:1|max:999',

            'shipping_method'        => 'required|string|in:' . implode(',', self::SHIPPING_METHODS),
            'shipping_city'          => 'required_if:shipping_method,np_department,np_postal_machine,np_courier|nullable|string|max:255',
            'shipping_city_ref'      => 'required_if:shipping_method,np_department,np_postal_machine,np_courier|nullable|string|max:255',
            'shipping_area'          => 'nullable|string|max:255',
            'shipping_warehouse'     => 'required_if:shipping_method,np_department,np_postal_machine|nullable|string|max:255',
            'shipping_warehouse_ref' => 'required_if:shipping_method,np_department,np_postal_machine|nullable|string|max:255',
            'shipping_address'       => 'required_if:shipping_method,np_courier|nullable|string|max:255',
            'shipping_message'       => 'required_if:shipping_method,other|nullable|string|max:255',
        ];
    }

    /**
     * Log which fields failed (names only, no customer data) so rejected
     * checkouts can be diagnosed from `docker compose logs app`.
     */
    protected function failedValidation(Validator $validator): void
    {
        Log::info('Order rejected by validation', [
            'fields' => array_keys($validator->errors()->messages()),
        ]);

        parent::failedValidation($validator);
    }

    /**
     * Any Ukrainian or international (E.164) number; the checkout sends
     * "+<country code> <number>" from the country picker.
     */
    private function phoneRule(): callable
    {
        $locale = Phone::resolveLocale($this->input('locale'));

        return function (string $attribute, mixed $value, callable $fail) use ($locale) {
            $error = Phone::validationError((string) $value, $locale);

            if ($error !== null) {
                $fail(__($error, ['attribute' => __('validation.attributes.client_phone', [], $locale)], $locale));
            }
        };
    }
}
