<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => $name,
            'main_category_id' => MainCategory::factory(),
            'slug' => Str::slug($name),
            'image_id' => Image::factory(),
            'description' => $this->faker->paragraph(),
            'title' => $this->faker->sentence()
        ];
    }
}
