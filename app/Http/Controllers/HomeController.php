<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Ana Sayfa ve Filtreli Haberler
    public function index(Request $request, $categorySlug = null)
    {
        $categories = Category::orderBy('name')->get();

        $newsQuery = News::with(['category', 'user', 'image'])->latest()->where('is_active', true);

        // Eğer kategori slug geldiyse, ona ait haberleri getir
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $newsQuery->where('category_id', $category->id);
        } else {
            $category = null;
        }

        // Sadece başlıkta arama yapsın
        if ($request->filled('search')) {
            $newsQuery->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $newsQuery->paginate(9);

        return view('welcome', compact('categories', 'news', 'category'));
    }

    // Haber Detayı
    public function show($slug)
    {
        $haber = News::with(['category', 'user', 'image'])->where('slug', $slug)->firstOrFail();
        // Okunma sayısını artır
        $haber->increment('views');
        return view('news.show', compact('haber'));
    }
}
