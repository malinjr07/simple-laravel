<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'sku' => $this->faker->unique()->bothify('??-####'),
            'stock' => $this->faker->numberBetween(0, 250),
            'is_active' => $this->faker->boolean(80), // 80% active
            'is_featured' => $this->faker->boolean(20), // 20% featured
            'is_on_sale' => $this->faker->boolean(30), // 30% on sale
            'sale_price' => function (array $attributes) {
                return $attributes['is_on_sale'] ? $this->faker->randomFloat(2, 5, $attributes['price'] * 0.8) : null;
            },
            'sale_start_date' => function (array $attributes) {
                return $attributes['is_on_sale'] ? $this->faker->dateTimeBetween('-1 month', 'now') : null;
            },
            'sale_end_date' => function (array $attributes) {
                return $attributes['is_on_sale'] ? $this->faker->dateTimeBetween('+1 day', '+3 months') : null;
            },
            'meta_title' => $name,
            'meta_description' => $this->faker->sentence(),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }
}
