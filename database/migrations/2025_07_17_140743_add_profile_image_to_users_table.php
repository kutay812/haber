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
        Schema::table('users', function (Blueprint $table) {
            // Mevcut profile_image sütununu değiştir veya yeniden oluştur
            if (Schema::hasColumn('users', 'profile_image')) {
                // Eğer zaten string türünde varsa, önce sil
                $table->dropColumn('profile_image');
            }
            
            // Veritabanı tabanlı resim depolama için yeni sütunlar
            $table->longText('profile_image')->nullable()->after('email')->comment('Base64 encoded image data');
            $table->string('profile_image_mime', 50)->nullable()->after('profile_image')->comment('Image MIME type (image/jpeg, image/png, etc.)');
            $table->integer('profile_image_size')->nullable()->after('profile_image_mime')->comment('Original file size in bytes');
            $table->timestamp('profile_image_updated_at')->nullable()->after('profile_image_size')->comment('Last image update timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'profile_image_mime',
                'profile_image_size',
                'profile_image_updated_at'
            ]);
        });
    }
};