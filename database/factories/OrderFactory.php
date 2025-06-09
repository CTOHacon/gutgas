<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name'      => $this->faker->name(),
            'client_email'     => $this->faker->safeEmail(),
            'client_phone'     => '+38 (' . $this->faker->numerify('###') . ') ' . $this->faker->numerify('###-##-##'),
            'client_message'   => $this->faker->optional()->sentence(),
            'cart_content'     => [
                [
                    'id'       => $this->faker->numberBetween(1, 100),
                    'name'     => [
                        'ua' => $this->faker->words(2, true),
                        'en' => $this->faker->words(2, true),
                        'ru' => $this->faker->words(2, true),
                    ],
                    'sku'      => $this->faker->regexify('[A-Z]{3}-[0-9]{3}'),
                    'price'    => $this->faker->numberBetween(50, 500),
                    'quantity' => $this->faker->numberBetween(1, 5),
                ]
            ],
            'shipping_message' => $this->faker->optional()->sentence(),
            'status_name'      => 'pending',
            'status_color'     => 'primary',
            'compleated'       => false,
        ];
    }

    /**
     * Create an order with multiple items
     */
    public function withMultipleItems(int $itemCount = 3): static
    {
        return $this->state(function (array $attributes) use ($itemCount) {
            $cartContent = [];
            for ($i = 0; $i < $itemCount; $i++) {
                $cartContent[] = [
                    'id'       => $this->faker->unique()->numberBetween(1, 1000),
                    'name'     => [
                        'ua' => $this->faker->words(2, true),
                        'en' => $this->faker->words(2, true),
                        'ru' => $this->faker->words(2, true),
                    ],
                    'sku'      => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{3}'),
                    'price'    => $this->faker->numberBetween(50, 500),
                    'quantity' => $this->faker->numberBetween(1, 5),
                ];
            }
            return [
                'cart_content' => $cartContent,
            ];
        });
    }

    /**
     * Create a completed order
     */
    public function completed(): static
    {
        return $this->state([
            'compleated'   => true,
            'status_name'  => 'completed',
            'status_color' => 'success',
        ]);
    }

}
