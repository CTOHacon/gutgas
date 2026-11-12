<?php

namespace App\Http\Requests\ProductRegistration;

use App\Support\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\App;

class StoreRequest extends FormRequest
{
    /** Name of the hidden anti-spam field. Real users never fill it. */
    public const HONEYPOT = 'website';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation messages should follow the locale of the page the form was sent from.
     */
    protected function prepareForValidation(): void
    {
        $locale = $this->input('locale');
        if (is_string($locale) && array_key_exists($locale, config('app.locales'))) {
            App::setLocale($locale);
        }
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'           => 'required|string|max:255',
            'last_name'            => 'required|string|max:255',
            // ua/ru: +38 (###) ###-##-##; other locales: international E.164
            'phone'                => ['required', 'string', 'max:32', $this->phoneRule()],
            'email'                => 'required|email|max:255',
            'city'                 => 'required|string|max:255',
            'address'              => 'nullable|string|max:1000',
            'model'                => 'nullable|string|max:255',
            'serial_number'        => 'required|string|max:255',
            'purchase_date'        => 'required|date|after:1990-01-01|before_or_equal:today',
            'price'                => 'required|integer|min:1|max:100000000',
            'purchase_place'       => 'required|string|max:255',
            'subscription_consent' => 'nullable|boolean',
            'locale'               => 'nullable|string|max:8',
        ];
    }

    private function phoneRule(): callable
    {
        $locale = Phone::resolveLocale($this->input('locale'));

        return function (string $attribute, mixed $value, callable $fail) use ($locale) {
            $error = Phone::validationError((string) $value, $locale);

            if ($error !== null) {
                $fail(__($error, ['attribute' => mb_strtolower(__('product-registration.label_phone', [], $locale))], $locale));
            }
        };
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];
        foreach (['first_name', 'last_name', 'phone', 'email', 'city', 'address', 'model', 'serial_number', 'purchase_date', 'price', 'purchase_place'] as $field) {
            $attributes[$field] = mb_strtolower(__('product-registration.label_' . $field));
        }

        return $attributes;
    }

    /**
     * Bots that fill the honeypot get a fake success, so they learn nothing.
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->isHoneypotFilled()) {
            throw new HttpResponseException(redirect()->back());
        }

        parent::failedValidation($validator);
    }

    public function isHoneypotFilled(): bool
    {
        return $this->filled(self::HONEYPOT);
    }
}
