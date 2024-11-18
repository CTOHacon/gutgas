<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Category;
use App\Models\MediaFile;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            // MediaFilesSeeder::class,
            // CategoriesSeeder::class,
            // AttributesSeeder::class,
        ]);

        $categories = Category::all();
        $mediaFiles = MediaFile::all();
        $attributeGroups = AttributeGroup::all();
        $attributes = Attribute::all();

        for ($i = 0; $i < 25; $i++) {
            $product = Product::factory()->create();
            $product->category()->associate($categories->random()->id);

            $randomAttributeGroups = $attributeGroups->random(3);
            $randomAttributeGroups->each(function ($attributeGroup) use ($product, $attributes) {
                $attribute = $attributes->where('attribute_group_id', $attributeGroup->id)->random();
                $product->attributes()->attach($attribute->id);
            });

            $product->mediaFile()->associate($mediaFiles->random()->id);
            // $product->mediaFiles()->attach($mediaFiles->random(4)->pluck('id'));
            $product->save();

            $product->productPage()->create([
                'title' => [
                    'en' => 'Product Page Title ' . $product->name,
                    'ua' => 'Заголовок Сторінки' . $product->name,
                ],
                'description' => [
                    'en' => 'Product Page Description ' . $product->description,
                    'ua' => 'Опис Сторінки' . $product->description,
                ],
            ]);
            $productPage = $product->productPage;
            $productPage->mediaGallery()->attach($mediaFiles->random(4)->pluck('id'));
            $productPage->characteristicIcons()->attach($mediaFiles->random(4)->pluck('id'));
            $productPage->documentations()->attach($mediaFiles->random(4)->pluck('id'));
            $productPage->save();

            $productPage->seoEntity()->update([
                'slug' => 'catalog/product-' . $product->id,
                'title' => [
                    'en' => 'Product Page Title ' . $product->name,
                    'ua' => 'Заголовок Сторінки' . $product->name,
                ],
                'description' => [
                    'en' => 'Product Page Description ' . $product->description,
                    'ua' => 'Опис Сторінки' . $product->description,
                ],
            ]);
        }
    }
}
