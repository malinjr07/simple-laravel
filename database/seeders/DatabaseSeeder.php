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

        // Create products first
        $products = Product::factory()->count(40)->create();

        // Count to track total media items
        $totalMediaCount = 0;

        // Create media associated with products and set the image_id
        foreach ($products as $product) {
            // Create 1-4 media for each product
            $mediaCount = rand(1, 4);
            $totalMediaCount += $mediaCount;

            // First media is primary
            $primaryMedia = Media::factory()
                ->primary()
                ->forProduct($product->id)
                ->create();

            // Update the product's image_id with the primary media's id
            $product->update([
                'image_id' => $primaryMedia->media_id
            ]);

            // Rest are not primary (if mediaCount > 1)
            if ($mediaCount > 1) {
                Media::factory()
                    ->count($mediaCount - 1)
                    ->forProduct($product->id)
                    ->create();
            }
        }

        // Calculate how many null product_id media to create (aim for ~5%)
        $nullProductMedia = 22;

        // Create small amount of media not associated with products
        Media::factory()->count($nullProductMedia)->create();

        $this->command->info('Created: ' . $totalMediaCount . ' product media and ' .
            $nullProductMedia . ' unassociated media');
        $this->command->info('Total media: ' . ($totalMediaCount + $nullProductMedia));
        $this->command->info('Percentage with null product_id: ' .
            round(($nullProductMedia / ($totalMediaCount + $nullProductMedia)) * 100, 2) . '%');
        $this->command->info('All products have an image_id set to their primary media');
    }
}
