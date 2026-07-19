<?php

namespace App\Services;

use App\Models\User;

class TestService
{
    public function getTestUserNewsImage()
    {
        $user = User::find(1);
        if (!$user) return ['error' => 'User not found', 'code' => 404];

        $news = $user->news()->first();
        if (!$news) return ['error' => 'News not found', 'code' => 404];

        // Eğer images ilişkisi yoksa $news->images() kısmını kendi modeline göre değiştir.
        $image = method_exists($news, 'images') ? $news->images()->first() : null;

        if ($image && $image->image) {
            return ['image_name' => $image->image->name];
        }

        return $news;
    }

    public function createTestUser()
    {
        return User::firstOrCreate(
            ['name' => 'Ali Kutay Tosun'],
            [
                'email' => env('DEFAULT_ADMIN_EMAIL', 'admin@example.com'),
                'password' => \Hash::make(env('DEFAULT_ADMIN_PASSWORD', 'secret'))
            ]
        );
    }
}
