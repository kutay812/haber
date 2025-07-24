<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data
        $users = User::all();
        $categories = Category::all();
        $images = Image::all();

        if ($users->isEmpty() || $categories->isEmpty() || $images->isEmpty()) {
            // Create sample data if none exists
            News::factory(20)->create();
        } else {
            // Use existing data
            News::factory(20)->make()->each(function ($news) use ($users, $categories, $images) {
                $news->user_id = $users->random()->id;
                $news->category_id = $categories->random()->id;
                $news->image_id = $images->random()->id;
                $news->save();
            });
        }
    }
}
