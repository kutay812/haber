<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Kaynak bilgileri
            $table->foreignId('news_source_id')->nullable()->after('user_id')
                  ->constrained('news_sources')->nullOnDelete();
            $table->string('source_url', 500)->nullable()->after('news_source_id');
            $table->string('source_author', 200)->nullable()->after('source_url');
            $table->timestamp('source_published_at')->nullable()->after('source_author');

            // Okuma deneyimi
            $table->unsignedSmallInteger('reading_time')->default(0)->after('views'); // dakika

            // Editoryal bayraklar
            $table->boolean('is_breaking')->default(false)->after('is_active');  // Son dakika
            $table->boolean('is_featured')->default(false)->after('is_breaking'); // Manşet
            $table->boolean('is_editor_pick')->default(false)->after('is_featured'); // Editör seçimi

            // Durum sistemi
            $table->enum('status', ['draft', 'pending', 'published', 'archived'])
                  ->default('published')->after('is_editor_pick');

            // SEO
            $table->string('meta_title', 200)->nullable()->after('slug');
            $table->string('meta_description', 300)->nullable()->after('meta_title');

            // Duplikasyon kontrolü
            $table->string('content_hash', 64)->nullable()->after('meta_description');

            // İndeksler
            $table->index('is_breaking');
            $table->index('is_featured');
            $table->index('is_editor_pick');
            $table->index('status');
            $table->index('source_url');
            $table->index('content_hash');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['news_source_id']);
            $table->dropColumn([
                'news_source_id', 'source_url', 'source_author', 'source_published_at',
                'reading_time', 'is_breaking', 'is_featured', 'is_editor_pick',
                'status', 'meta_title', 'meta_description', 'content_hash',
            ]);
        });
    }
};
