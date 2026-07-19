<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    // TAG > HABERLER ilişkisi
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tag')
                    ->withTimestamps();
    }

    // Slug otomatik oluşturma
    protected static function booted()
    {
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = \Illuminate\Support\Str::slug($tag->name);
            }
        });
    }
}
