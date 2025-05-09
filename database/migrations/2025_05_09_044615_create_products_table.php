<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);

            // Relationship with Categories
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // Relationship with Media
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('media_id')->on('media')->onDelete('cascade');

            // Relationship with Tags
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

            $table->string('sku')->unique()->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_on_sale')->default(false);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->date('sale_start_date')->nullable();
            $table->date('sale_end_date')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('meta_robots')->nullable();
            $table->string('meta_canonical')->nullable();
            $table->string('meta_author')->nullable();
            $table->string('meta_og_title')->nullable();
            $table->string('meta_og_description')->nullable();
            $table->string('meta_og_image')->nullable();
            $table->string('meta_og_url')->nullable();
            $table->string('meta_og_type')->nullable();
            $table->string('meta_twitter_card')->nullable();
            $table->string('meta_twitter_site')->nullable();
            $table->string('meta_twitter_title')->nullable();
            $table->string('meta_twitter_description')->nullable();
            $table->string('meta_twitter_image')->nullable();
            $table->string('meta_twitter_url')->nullable();
            $table->string('meta_twitter_creator')->nullable();
            $table->string('meta_twitter_app')->nullable();
            $table->string('meta_twitter_app_id')->nullable();
            $table->string('meta_twitter_app_name')->nullable();
            $table->string('meta_twitter_app_url')->nullable();
            $table->string('meta_twitter_app_image')->nullable();
            $table->string('meta_twitter_app_description')->nullable();
            $table->string('meta_twitter_app_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
