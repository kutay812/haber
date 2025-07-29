<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    // Ana Sayfa ve Kategori/Arama Filtreli Liste
    public function index(Request $request, $categorySlug = null)
    {
        $categories = Category::orderBy('name')->get();

        $newsQuery = News::with(['category', 'user', 'image'])->latest()->where('is_active', true);

        $category = null;
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $newsQuery->where('category_id', $category->id);
        }

        if ($request->filled('search')) {
            $newsQuery->where('title', 'like', '%' . $request->search . '%');
        }

        $news = $newsQuery->paginate(9);

        $canEditNews = Auth::check() && Auth::user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']);

        return view('welcome', compact('categories', 'news', 'category', 'canEditNews'));
    }

    // HABER KAYDETME (STORE)
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['Admin', 'Editor', 'Super Admin'])) {
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string|max:300',
            'content'     => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240'
        ]);

        // Benzersiz SLUG üret
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // Resmi fiziksel olarak public/storage/news-images klasörüne kaydet
        $imageId = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $publicPath = 'storage/news-images/';
            $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $destination = public_path($publicPath);

            // Klasörü oluştur (yoksa)
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $imageFile->move($destination, $fileName);

            // Image DB kaydı (sadece yol!)
            $image = Image::create([
                'path' => 'news-images/' . $fileName,
                'name' => $fileName
            ]);
            $imageId = $image->id;
        }

        // Haberi kaydet (image_id ile!)
        $news = News::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'content'     => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id'     => $user->id,
            'is_active'   => true,
            'slug'        => $slug,
            'image_id'    => $imageId,
        ]);

        return redirect()->route('home')->with('success', 'Haber başarıyla eklendi!');
    }

    // HABER DETAYI
    public function show($slug)
    {
        $haber = News::with(['category', 'user', 'image'])->where('slug', $slug)->firstOrFail();
        $haber->increment('views');
        return view('news.show', compact('haber'));
    }

    // HABER GÜNCELLEME (UPDATE)
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['Admin', 'Editor', 'Super Admin'])) {
            abort(403, 'Bu işlemi yapmaya yetkiniz yok.');
        }

        $haber = News::with('image')->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string|max:300',
            'content'     => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240'
        ]);

        $haber->title       = $validated['title'];
        $haber->description = $validated['description'];
        $haber->content     = $validated['content'];
        $haber->category_id = $validated['category_id'];

        // Eğer yeni resim geldiyse:
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $publicPath = 'storage/news-images/';
            $fileName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $destination = public_path($publicPath);

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $imageFile->move($destination, $fileName);

            // Eski resmi sil
            if ($haber->image && $haber->image->path && file_exists(public_path('storage/' . $haber->image->path))) {
                @unlink(public_path('storage/' . $haber->image->path));
            }

            if ($haber->image) {
                $haber->image->update([
                    'path' => 'news-images/' . $fileName,
                    'name' => $fileName
                ]);
            } else {
                $image = Image::create([
                    'path' => 'news-images/' . $fileName,
                    'name' => $fileName
                ]);
                $haber->image_id = $image->id;
            }
        }
        $haber->save();

        return redirect()->route('news.show', $haber->slug)->with('success', 'Haber başarıyla güncellendi.');
    }
}
