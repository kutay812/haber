<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                    // Kaynak adı: "Anadolu Ajansı"
            $table->string('slug', 170)->unique();
            $table->string('url', 500);                      // RSS/API URL
            $table->enum('type', ['rss', 'api', 'scraper'])->default('rss');
            $table->string('logo_url', 500)->nullable();     // Kaynak logosu
            $table->string('website_url', 500)->nullable();  // Kaynak ana sayfası
            $table->unsignedTinyInteger('reliability_score')->default(80); // 0-100 güvenilirlik
            $table->foreignId('default_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_publish')->default(false); // Otomatik yayınla mı?
            $table->unsignedInteger('fetch_interval_minutes')->default(30);
            $table->timestamp('last_fetched_at')->nullable();
            $table->unsignedInteger('total_fetched')->default(0);
            $table->unsignedInteger('failed_fetches')->default(0);
            $table->json('category_mappings')->nullable();   // Kaynak kategori → yerel kategori eşleştirmesi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
