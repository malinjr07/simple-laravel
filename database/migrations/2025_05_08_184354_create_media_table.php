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
        Schema::create('media', function (Blueprint $table) {
            $table->id('media_id');
            $table->string('url')->unique();
            $table->string('fileName')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('local');
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamp('media_created_at')->nullable();
            $table->timestamp('media_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
