<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $filename = $this->faker->unique()->word . $extension;
        return [
            'url' => $this->faker->imageUrl(640, 480, 'cats'),
            'fileName' => $filename,
            'extension' => $extension,
            'mime_type' => 'image/' . $extension,
            'disk' => $this->faker->randomElement(['public', 'local']),
            'path' => 'media/' . $filename,
            'size' => $this->faker->numberBetween(100000, 999999),
            'product_id' => null,
        ];
    }
}
