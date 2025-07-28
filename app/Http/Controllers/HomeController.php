<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    // Ana Sayfa ve Filtreli Haberler
    public function index(Request $request, $categorySlug = null)
    {
        $categories = Category::orderBy('name')->get();

        $newsQuery = News::with(['category', 'user', 'image'])->latest()->where('is_active', true);

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $newsQuery->where('category_id', $category->id);
        } else {
            $category = null;
        }

        if ($request->filled('search')) {
            $newsQuery->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $newsQuery->paginate(9);

        $canEditNews = false;
        if (Auth::check() && Auth::user()->hasAnyRole(['Admin', 'Editor', 'Super Admin'])) {
            $canEditNews = true;
        }

        return view('welcome', compact('categories', 'news', 'category', 'canEditNews'));
    }

    // Haber Detayı
    public function show($slug)
    {
        $haber = News::with(['category', 'user', 'image'])->where('slug', $slug)->firstOrFail();
        $haber->increment('views');
        return view('news.show', compact('haber'));
    }

    // Haber Güncelleme (inline formdan)
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole(['Admin', 'Editor', 'Super Admin'])) {
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        $haber = News::with('image')->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240', // 10MB
        ]);

        $haber->title = $validated['title'];
        $haber->content = $validated['content'];
        $haber->save();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');

            if ($haber->image) {
                Storage::disk('public')->delete($haber->image->path);
                $haber->image->update(['path' => $path]);
            } else {
                $haber->image()->create(['path' => $path]);
            }
        }

        return redirect()->route('news.show', $haber->slug)->with('success', 'Haber başarıyla güncellendi.');
    }
}
