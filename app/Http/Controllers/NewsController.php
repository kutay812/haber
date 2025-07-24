<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)->orderBy('created_at', 'desc')->paginate(10);
        return view('news.index', compact('news'));
    }

    public function show($id)
    {
        $news = News::where('is_active', true)->findOrFail($id);
        return view('news.show', compact('news'));
    }
}
