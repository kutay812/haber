<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Composite indexes for frequent queries
            $table->index(['status', 'is_active', 'created_at'], 'news_status_active_created_idx');
            $table->index(['is_featured', 'status', 'is_active'], 'news_featured_status_idx');
            $table->index(['is_breaking', 'status', 'is_active'], 'news_breaking_status_idx');
            $table->index(['category_id', 'status', 'is_active'], 'news_category_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_status_active_created_idx');
            $table->dropIndex('news_featured_status_idx');
            $table->dropIndex('news_breaking_status_idx');
            $table->dropIndex('news_category_status_idx');
        });
    }
};
