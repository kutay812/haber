<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'news_count' => News::count(),
            'category_count' => Category::count(),
            'user_count' => User::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 