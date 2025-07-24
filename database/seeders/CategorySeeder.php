<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MainCategory;
use App\Models\Image;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategoryIds = MainCategory::pluck('id')->toArray();
        $imageIds = Image::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Category::create([
                'name'             => fake()->unique()->word(), // isim de unique
                'main_category_id' => fake()->randomElement($mainCategoryIds),
                'image_id'         => fake()->randomElement($imageIds),
                'slug'             => fake()->unique()->slug() . '-' . $i, // kesin unique!
                'description'      => fake()->sentence(10),
                'title'            => fake()->sentence(3),
            ]);
        }

        // Eğer test tekrar çalışacaksa faker'ı sıfırla:
        fake()->unique($reset = true);
    }
}
