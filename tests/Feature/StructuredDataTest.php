<?php

namespace Tests\Feature;

use App\Http\Controllers\ProductPageController;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductPage;
use App\Models\SeoEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    private function seoEntity(string $slug, string $type, int $id, array $title, array $extra = []): SeoEntity
    {
        return SeoEntity::create(array_merge([
            'slug'                 => $slug,
            'seo_entiteable_type'  => $type,
            'seo_entiteable_id'    => $id,
            'title'                => $title,
            'description'          => ['ua' => 'Опис', 'ro' => 'Descriere'],
            'is_active'            => ['ua' => true, 'ro' => true],
        ], $extra));
    }

    /** Product page whose slug contains the "ro" locale code ("profesiinii"). */
    private function productFixture(): Product
    {
        $media = MediaFile::withoutEvents(fn () => MediaFile::create([
            'name' => 'hose', 'extension' => 'png', 'path' => 'app/public/media/hose.png',
            'url'  => '/storage/media/hose.png', 'size' => '1', 'mime_type' => 'image/png',
        ]));

        $product = Product::factory()->create([
            'sku'           => 'GG-TEST',
            'price'         => 599,
            'stock'         => 3,
            'name'          => ['ua' => 'Шланг газовий', 'ro' => 'Furtun de gaz'],
            'description'   => ['ua' => '<p>Опис <b>шланга</b></p>', 'ro' => '<p>Descriere</p>'],
            'media_file_id' => $media->id,
        ]);

        $group = AttributeGroup::create(['name' => ['ua' => 'Довжина', 'ro' => 'Lungime']]);
        $attribute = Attribute::create(['attribute_group_id' => $group->id, 'name' => ['ua' => '1,2 м', 'ro' => '1,2 m']]);
        $product->attributes()->attach($attribute->id);

        $productPage = ProductPage::create([
            'product_id'    => $product->id,
            'title'         => ['ua' => '', 'ro' => ''],
            'media_gallery' => [],
        ]);

        $this->seoEntity('/', Page::class, 1, ['ua' => 'Головна', 'ro' => 'Acasă']);
        $this->seoEntity('catalog', Page::class, 2, ['ua' => 'Каталог', 'ro' => 'Catalog']);
        // ProductPage (Seoable) creates its SeoEntity on create; fill it in.
        $productPage->seoEntity()->firstOrFail()->update([
            'slug'        => 'catalog/profesiinii-shlang',
            'title'       => ['ua' => 'Шланг - Купити в Україні', 'ro' => 'Furtun - Cumpără'],
            'description' => ['ua' => 'Опис', 'ro' => 'Descriere'],
            'is_active'   => ['ua' => true, 'ro' => true],
            'controller'  => ProductPageController::class,
            'action'      => 'show',
        ]);

        return $product;
    }

    public function test_breadcrumbs_strip_only_leading_locale_and_use_product_name(): void
    {
        $this->productFixture();

        $this->withoutVite()->get('/ro/catalog/profesiinii-shlang')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('breadcrumbs', [
                    ['title' => 'Acasă', 'slug' => '/ro'],
                    ['title' => 'Catalog', 'slug' => '/ro/catalog'],
                    ['title' => 'Furtun de gaz', 'slug' => '/ro/catalog/profesiinii-shlang'],
                ])
                ->etc());
    }

    public function test_product_structured_data(): void
    {
        $this->productFixture();

        $this->withoutVite()->get('/ua/catalog/profesiinii-shlang')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('productStructuredData.@context', 'https://schema.org')
                ->where('productStructuredData.name', 'Шланг газовий')
                ->where('productStructuredData.description', 'Опис шланга')
                ->where('productStructuredData.image', [url('/storage/media/hose.png')])
                ->missing('productStructuredData.mpn')
                ->where('productStructuredData.offers.priceCurrency', 'UAH')
                ->where('productStructuredData.offers.url', url('ua/catalog/profesiinii-shlang'))
                ->where('productStructuredData.offers.itemCondition', 'https://schema.org/NewCondition')
                ->where('productStructuredData.offers.availability', 'https://schema.org/InStock')
                ->where('productStructuredData.offers.seller.@id', url('/') . '/#organization')
                ->where('productStructuredData.offers.hasMerchantReturnPolicy.merchantReturnDays', 14)
                ->where('productStructuredData.offers.hasMerchantReturnPolicy.applicableCountry', 'UA')
                ->where('productStructuredData.offers.hasMerchantReturnPolicy.merchantReturnLink', url('ua/return'))
                ->where('productStructuredData.additionalProperty.0.name', 'Довжина')
                ->where('productStructuredData.additionalProperty.0.value', '1,2 м')
                ->etc());
    }
}
