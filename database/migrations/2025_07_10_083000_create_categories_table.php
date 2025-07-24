<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Ana kategoriye ait ilişki (bazı kategoriler ana kategoriye bağlı olmayabilir)
            $table->foreignId('main_category_id')
                  ->nullable()
                  ->constrained('main_categories')
                  ->onDelete('cascade');

            $table->string('name', 90);
            $table->text('description')->nullable();
            $table->string('title')->nullable();

            // Opsiyonel görsel
            $table->foreignId('image_id')
                  ->nullable()
                  ->constrained('images')
                  ->onDelete('set null');

            $table->string('slug', 90)->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
