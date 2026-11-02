<?php

namespace App\Services\I18n;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductFilter;
use App\Models\ProductFilterValue;
use App\Models\ProductPage;
use App\Models\ProductsGroup;
use App\Models\RequiredProductsGroup;
use App\Models\SeoEntity;
use Illuminate\Database\Eloquent\Model;

class ContentCatalog
{
    /** Text-bearing translatable fields per model (ordered, deterministic). */
    public const MODEL_FIELDS = [
        Product::class => ['name', 'description', 'price_label'],
        ProductFilterValue::class => ['value'],
        Attribute::class => ['name'],
        AttributeGroup::class => ['name'],
        ProductFilter::class => ['name'],
        ProductsGroup::class => ['name'],
        RequiredProductsGroup::class => ['name'],
        Page::class => ['title', 'content'],
        ProductPage::class => ['title', 'description'],
        SeoEntity::class => ['title', 'breadcrumbs_title', 'description'],
    ];

    /** Per-locale meta tables (config/meta.php custom tables). */
    public const META_TABLES = ['productPages_meta', 'page_meta', 'productsGroups_meta'];

    /** Meta types whose value is a JSON document. */
    public const META_JSON_TYPES = ['collection', 'json', 'array'];

    /** Meta types that carry text (anything else, e.g. integer/boolean, is not exported). */
    public const META_TEXT_TYPES = [null, 'string', 'collection', 'json', 'array'];

    public static function newModelQuery(string $class)
    {
        /** @var Model $class */
        return $class::query()->setEagerLoads([])->orderBy('id');
    }

    public static function translation(Model $model, string $field, string $locale): ?string
    {
        $translations = $model->getTranslations($field);
        $value = $translations[$locale] ?? null;

        return is_string($value) && trim($value) !== '' ? $value : null;
    }

    /** Decode a meta/setting storage string into what the export carries. */
    public static function decodeStored(?string $stored, bool $jsonType): mixed
    {
        if ($stored === null || trim($stored) === '') {
            return null;
        }
        if ($jsonType) {
            $decoded = json_decode($stored, true);

            return is_array($decoded) && $decoded !== [] ? $decoded : null;
        }

        return $stored;
    }

    /** Encode a value the way the source storage does (arrays as plain json_encode). */
    public static function encodeStored(mixed $value): string
    {
        return is_array($value) ? json_encode($value) : (string) $value;
    }

    public static function isEmptyStored(?string $stored): bool
    {
        return $stored === null || in_array(trim($stored), ['', '[]', '{}'], true);
    }
}
