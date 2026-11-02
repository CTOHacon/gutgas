<?php

namespace App\Services\I18n;

/**
 * Rewrites internal locale prefixes (/ua/... -> /ka/...) inside translated
 * strings, HTML attributes and nested arrays. Everything else is left untouched.
 */
class LinkRewriter
{
    /**
     * @param mixed $value string or (nested) array of strings
     * @return mixed same shape as $value
     */
    public static function rewrite(mixed $value, string $source, string $target): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => self::rewrite($item, $source, $target), $value);
        }

        if (!is_string($value) || $value === '') {
            return $value;
        }

        $src = preg_quote($source, '#');

        // Absolute URLs anywhere in the text: https://gutgas.eu/ua/..., https://www.gutgas.eu/ua
        $value = preg_replace(
            '#(https?://(?:www\.)?gutgas\.eu)/' . $src . '(?=/|$|[?\\#"\'\s<>)])#u',
            '$1/' . $target,
            $value
        );

        // Relative paths inside href/src/action attributes
        $value = preg_replace(
            '#(\b(?:href|src|action)\s*=\s*["\'])/' . $src . '(?=/|[?\\#"\'])#u',
            '$1/' . $target,
            $value
        );

        // The whole string is a relative path
        return preg_replace('#^/' . $src . '(?=/|$|[?\\#])#u', '/' . $target, $value);
    }
}
