<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'main_category_id',
        'description',
        'title',
        'image_id',
        'slug'
    ];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
