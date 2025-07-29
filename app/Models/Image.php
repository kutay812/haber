<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'images';

    protected $fillable = [
        'name',
        'path',
    ];

    // Haber ile ilişki (şu an hasMany, aslında çoğunlukla One-to-One gibi kullanıyorsun)
    public function news()
    {
        return $this->hasMany(\App\Models\News::class, 'image_id');
    }
}
