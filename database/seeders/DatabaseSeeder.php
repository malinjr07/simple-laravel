<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory()->count(80)->create();
        User::factory()->count(15)->create();

        // Create products
        $products = Product::factory()->count(40)->create();

        // Create media
        $mediaItems = Media::factory()->count(100)->create();

        // Associate media with products
        foreach ($products as $product) {
            $associatedMedia = $mediaItems->random(rand(1, 5));

            foreach ($associatedMedia as $index => $media) {
                $product->media()->attach($media->media_id, [
                    'is_primary' => $index === 0,
                ]);
            }
        }

        $this->command->info('Database seeding completed successfully.');
    }
}
