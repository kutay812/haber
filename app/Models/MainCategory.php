<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use HasFactory, SoftDeletes; // ✅ SoftDeletes eklenmeli çünkü migration'da kullanıyorsun

    protected $table = 'main_categories'; // ✅ İngilizce "i" ile doğru

    protected $fillable = [
        'name',
        'image_id',
        'slug',
        'parent_id',
    ];

    // ✅ İlişkilendirmeler (isteğe bağlı ama önerilir)
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
