<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use Illuminate\Database\Seeder;

class MainCategorySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            MainCategory::create([
                'name' => "Ana Kategori $i",
                'slug' => "ana-kategori-$i",
                'image_id' => null,
                'parent_id' => null,
            ]);
        }
    }
}
