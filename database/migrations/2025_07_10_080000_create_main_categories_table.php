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
        Schema::create('main_categories', function (Blueprint $table) {
            $table->id();

            // Üst kategori ilişkisi (nullable)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('main_categories')
                  ->onDelete('cascade');

            // Kategori adı
            $table->string('name', 90);

            // Opsiyonel görsel ilişkisi
            $table->foreignId('image_id')
                  ->nullable()
                  ->constrained('images')
                  ->onDelete('set null');

            // Slug benzersiz olacak
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
        Schema::dropIfExists('main_categories');
    }
};
