<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general'); // general, seo, social, appearance
            $table->string('type', 20)->default('text'); // text, boolean, json, number
            $table->timestamps();
        });

        // Varsayılan ayarları ekle
        $now = now();
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => 'HaberPortal', 'group' => 'general', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'site_description', 'value' => 'En güncel ve güvenilir haberler', 'group' => 'general', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'site_logo', 'value' => null, 'group' => 'appearance', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'breaking_news_enabled', 'value' => '1', 'group' => 'general', 'type' => 'boolean', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'auto_fetch_enabled', 'value' => '1', 'group' => 'general', 'type' => 'boolean', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'default_theme', 'value' => 'light', 'group' => 'appearance', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
