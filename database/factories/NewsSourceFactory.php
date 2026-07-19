<?php

namespace Database\Factories;

use App\Models\NewsSource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsSourceFactory extends Factory
{
    protected $model = NewsSource::class;

    public function definition(): array
    {
        $name = $this->faker->company;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'url' => $this->faker->url,
            'type' => 'rss',
            'reliability_score' => 80,
            'default_category_id' => Category::factory(),
            'is_active' => true,
            'auto_publish' => true,
            'fetch_interval_minutes' => 30,
        ];
    }
}
