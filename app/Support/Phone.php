<?php

namespace App\Support;

class Phone
{
    /** Locales whose phone picker defaults to Ukraine (config('app.ua_phone_locales')). */
    public static function usesUaMask(?string $locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();

        return in_array($locale, config('app.ua_phone_locales', ['ua', 'ru']), true);
    }

    /**
     * Resolve the locale from the request's "locale" field, falling back to the app locale.
     */
    public static function resolveLocale(mixed $requested): string
    {
        if (is_string($requested) && array_key_exists($requested, config('app.locales'))) {
            return $requested;
        }

        return app()->getLocale();
    }

    /** Ukrainian: 0XXXXXXXXX or 380XXXXXXXXX in any formatting. */
    public static function isUkrainian(string $value): bool
    {
        return (bool) preg_match('/^(38)?0\d{9}$/', preg_replace('/\D/', '', $value));
    }

    /** International E.164: "+", then 8-15 digits, first digit non-zero (spaces, dashes, parentheses ignored). */
    public static function isInternational(string $value): bool
    {
        $compact = preg_replace('/[\s\-().]/', '', $value);

        return (bool) preg_match('/^\+[1-9]\d{7,14}$/', $compact);
    }

    /**
     * Returns null when valid, otherwise the validation message key.
     * Deliberately lenient: the form sends "+<country code> <number>" from the
     * country picker, so any Ukrainian or international (E.164) number passes.
     */
    public static function validationError(string $value, ?string $locale = null): ?string
    {
        return static::isUkrainian($value) || static::isInternational($value) ? null : 'validation.phone';
    }
}
