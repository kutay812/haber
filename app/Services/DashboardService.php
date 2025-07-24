<?php

namespace App\Services;

use App\Models\News;
use App\Models\User;
use App\Models\Category;

class DashboardService
{
    // Sadece admin paneli dashboard istatistikleri
    public function getDashboardStats()
    {
        return [
            'total_news'      => News::count(),
            'total_users'     => User::count(),
            'total_categories'=> Category::count(),
            'recent_news'     => News::with(['category', 'user'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get(),
            'recent_users'    => User::with('roles')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get(),
        ];
    }
}
