<?php

namespace App\Models;

use App\Traits\HasFrontendPaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations, HasFrontendPaginator;

    protected $guarded = [];

    public $translatable = [
        'name',
        'description',
        'price_label'
    ];

    public $with = [
        'mediaFile.thumbnail',
        'seoEntity:slug'
    ];

    // CATEGORIES
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        // return $this->category->get()->ancestorsAndSelf();
        return $this->category()->ancestorsAndSelf();
    }

    public function productsGroups()
    {
        return $this->belongsToMany(ProductsGroup::class);
    }

    public function descedantCategories()
    {
        // return $this->category->get()->descendantsAndSelf();
        return $this->category()->descendantsAndSelf();
    }

    // ATTRIBUTES
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function productFilterValues()
    {
        return $this->belongsToMany(ProductFilterValue::class);
    }

    public function attributeGroups()
    {
        // get AttributeGroups with attributes that are attached to this product
        return AttributeGroup::whereHas('attributes.products', function ($query) {
            $query->where('products.id', $this->id);
        })->with([
                    'attributes' => function ($query) {
                        $query->whereHas('products', function ($query) {
                            $query->where('products.id', $this->id);
                        });
                    }
                ])->get();
    }

    // PRODUCT PAGE
    public function productPage()
    {
        return $this->hasOne(ProductPage::class);
    }

    // SEO ENTITY
    public function seoEntity()
    {
        return $this->hasOneThrough(SeoEntity::class, ProductPage::class, 'product_id', 'seo_entiteable_id', 'id', 'id')->where('seo_entiteable_type', ProductPage::class);
    }

    // Media FIle
    public function mediaFile()
    {
        return $this->belongsTo(MediaFile::class);
    }

    // Required Products Groups
    public function requiredProductsGroups()
    {
        return $this->belongsToMany(RequiredProductsGroup::class, 'product_required_products_group', 'product_id', 'required_products_group_id');
    }

    public function requiredProducts()
    {
        // merge products relation from each required products group
        return $this->requiredProductsGroups->map(function ($requiredProductsGroup) {
            return $requiredProductsGroup->products;
        })->flatten();
    }

    // OTHER
    public function searchByName($query)
    {
        return $this->where('name', 'like', "%$query%")->orWhere('sku', 'like', "%$query%");
    }

    public function syncProductRelationsData($data)
    {
        $this->attributes()->sync($data['attributes']);

        $this->productFilterValues()->sync($data['product_filter_values']);

        $this->productsGroups()->sync($data['products_groups']);

        $this->requiredProductsGroups()->sync($data['required_products_groups']);

        $this->mediaFile()->associate($data['media_file'] ? $data['media_file']['id'] : null);

        $this->save();
    }

    public function generateStructuredData(?ProductPage $productPage = null)
    {
        $locale = app()->currentLocale();
        $slug = $productPage?->seoEntity?->slug ?? $this->seoEntity?->slug;

        $images = collect([$this->mediaFile?->url])
            ->merge($productPage?->mediaGallery?->pluck('url') ?? [])
            ->filter()
            ->map(fn ($path) => url($path))
            ->unique()
            ->values()
            ->all();

        $additionalProperty = $this->attributes()->with('attributeGroup')->get()
            ->map(fn ($attribute) => [
                "@type" => "PropertyValue",
                "name"  => $attribute->attributeGroup->name,
                "value" => $attribute->name,
            ])
            ->values()
            ->all();

        return array_filter([
            "@context"           => "https://schema.org",
            "@type"              => "Product",
            "name"               => $this->name,
            "image"              => $images,
            "description"        => trim(strip_tags((string) $this->description)),
            "sku"                => $this->sku,
            "brand"              => [
                "@type" => "Brand",
                "name"  => "GUTGAS",
            ],
            "offers"             => [
                "@type"                   => "Offer",
                "url"                     => url($locale . '/' . $slug),
                "priceCurrency"           => "UAH",
                "price"                   => $this->price,
                "itemCondition"           => "https://schema.org/NewCondition",
                "availability"            => $this->stock > 0
                    ? "https://schema.org/InStock"
                    : "https://schema.org/OutOfStock",
                "seller"                  => ["@id" => url('/') . '/#organization'],
                "hasMerchantReturnPolicy" => self::merchantReturnPolicy($locale),
            ],
            "additionalProperty" => $additionalProperty,
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);
    }

    /**
     * Mirrors the public returns page (/{locale}/return): 14 days, returned
     * by post, the buyer pays the return shipping (Consumer Protection Law, art. 9).
     */
    public static function merchantReturnPolicy(string $locale): array
    {
        return [
            "@type"                => "MerchantReturnPolicy",
            "applicableCountry"    => "UA",
            "returnPolicyCategory" => "https://schema.org/MerchantReturnFiniteReturnWindow",
            "merchantReturnDays"   => 14,
            "returnMethod"         => "https://schema.org/ReturnByMail",
            "returnFees"           => "https://schema.org/ReturnFeesCustomerResponsibility",
            "merchantReturnLink"   => url($locale . '/return'),
        ];
    }

}
