<?php

namespace Database\Factories;

use App\Models\NewImages;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewImagesFactory extends Factory
{
    protected $model = NewImages::class;

    public function definition(): array
    {
        return [
            'news_id' => \App\Models\News::factory(),
            'image_id' => \App\Models\Image::factory(),
        ];
    }
}