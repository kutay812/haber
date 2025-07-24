<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news'; // tablo adını elle belirtebilirsin, opsiyonel

    protected $fillable = [
        'title',
        'description',
        'content',
        'category_id',
        'image_id',
        'user_id',
        'is_active',
        'views',
        'slug',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'views'        => 'integer',
        'image_id'     => 'integer',
        'category_id'  => 'integer',
        'user_id'      => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
        // 'published_at' => 'datetime', // eğer yayınlanma tarihi varsa ekle
    ];

    // HABER > KATEGORİ ilişkisi
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // HABER > YAZAN KULLANICI ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // HABER > GÖRSEL ilişkisi
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    // Eğer haber aktif değilse query'de hariç tutmak için (scope örneği)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
