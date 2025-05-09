<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $extension = $this->faker->randomElement(['jpeg', 'png', 'jpg']);
        // Use a combination of words plus random string to ensure uniqueness
        $fileName = $this->faker->word . '-' . $this->faker->word . '-' . Str::random(8);
        $path = "uploads/images/{$fileName}.{$extension}";

        $width = $this->faker->numberBetween(200, 800);
        $height = $this->faker->numberBetween(200, 800);

        // Use placeholder service for URL
        $url = "https://picsum.photos/{$width}/{$height}";

        return [
            'url' => $url,
            'fileName' => $fileName,
            'extension' => $extension,
            'mime_type' => 'image/' . $extension,
            'disk' => $this->faker->randomElement(['public', 'local']),
            'path' => $path,
            'size' => $this->faker->numberBetween(100000, 999999),
            'is_primary' => $this->faker->boolean(20), // 20% chance of being primary
            'product_id' => null,
            'media_created_at' => now(),
            'media_updated_at' => now(),
        ];
    }

    /**
     * Configure the model factory to create a primary media.
     *
     * @return $this
     */
    public function primary()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_primary' => true,
            ];
        });
    }

    /**
     * Configure the model factory to associate with a product.
     *
     * @param int $productId
     * @return $this
     */
    public function forProduct($productId)
    {
        return $this->state(function (array $attributes) use ($productId) {
            return [
                'product_id' => $productId,
            ];
        });
    }
}
