<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'content',
        'category_id',
        'image_id',
        'user_id',
        'is_active',
        'views',
        'slug'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views' => 'integer',
        'image_id' => 'integer',
        'category_id' => 'integer',
        'user_id' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
