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
            $table->id();
            $table->string('url')->unique();
            // $table->string('type')->default('image');
            $table->string('name')->nullable();
            $table->string('extension')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('local');
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
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
