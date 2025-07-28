<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'user_id',
        'content',
    ];

    // YORUM > KULLANICI ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // YORUM > HABER ilişkisi
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
