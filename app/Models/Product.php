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

    public $translatable = ['name', 'description'];

    public $with = ['mediaFile.thumbnail', 'seoEntity:slug'];

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

    public function generateStructuredData()
    {
        $attributes = $this->attributes()->get();
        $attributesForStructuredData = [];
        foreach ($attributes as $attribute) {
            $attributesForStructuredData[] = [
                'name' => $attribute->attributeGroup->name,
                'value' => $attribute->name
            ];
        }
        return [
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => $this->name,
            "image" => [
                url($this->mediaFile->thumbnail->url)
            ],
            "description" => $this->description,
            "sku" => $this->sku,
            "mpn" => $this->sku,
            "brand" => [
                "@type" => "Brand",
                "name" => "Gutgas"
            ],
            "offers" => [
                "@type" => "Offer",
                "url" => url(app()->currentLocale() . '/' . $this->seoEntity->slug),
                "priceCurrency" => "UAH",
                "price" => $this->price,
                "availability" => $this->stock > 0 ?
                    "https://schema.org/InStock" :
                    "https://schema.org/OutOfStock",
            ],
            "additionalProperty" => array_map(function ($attribute) {
                return [
                    "@type" => "PropertyValue",
                    "name" => $attribute['name'],
                    "value" => $attribute['value']
                ];
            }, $attributesForStructuredData)
        ];
    }
}
