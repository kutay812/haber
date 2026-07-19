<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'news_id' => News::factory(),
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph,
        ];
    }
}
