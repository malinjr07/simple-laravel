<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Media;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Add this import

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory()->count(80)->create();
        User::factory()->count(15)->create();

        $tags = Tag::factory()->count(20)->create();

        // Create products
        $products = Product::factory()->count(40)->create();

        // Create media
        $mediaItems = Media::factory()->count(100)->create();

        // Associate media with products
        foreach ($products as $product) {
            $associatedMedia = $mediaItems->random(rand(5, 8)); // Ensure at least 5 images

            foreach ($associatedMedia as $index => $media) {
                $product->media()->attach($media->media_id, [
                    'is_primary' => $index === 0,
                ]);
            }

            // Associate at least 5 random tags with each product
            $randomTags = $tags->random(rand(5, 8)); // 5-8 tags per product
            $product->tags()->attach($randomTags);

            // Update the tag_id field with the first tag's ID
            $product->update(['tag_id' => $randomTags->first()->id]);
        }

        $this->command->info('Database seeding completed successfully.');
        $this->command->info('All products have at least 5 tags and 5 images.');
    }
}
