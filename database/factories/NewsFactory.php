<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(3, true),
            'image_id' => Image::factory(),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'is_active' => true,
            'views' => $this->faker->numberBetween(0, 1000)
        ];
    }
}
