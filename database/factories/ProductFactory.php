<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = config('app.locales');
        $name = [];
        $description = [];
        foreach ($locales as $locale => $localeLable) {
            $name[$locale] = $localeLable . '-' . $this->faker->unique()->word();
            $description[$locale] = $localeLable . '-' . $this->faker->text();
        }
        return [
            'sku' => $this->faker->unique()->word(),
            'name' => $name,
            'price' => $this->faker->numberBetween(100, 1000),
            'stock' => $this->faker->numberBetween(1, 100),
            'description' => $description,
        ];
    }
}
