<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    // Haberleri listele (admin tablosu)
    public function index(Request $request)
    {
        $query = News::query()
            ->with(['category', 'user'])
            ->when($request->filled('search'), function($q) use ($request) {
                $searchTerm = $request->search;
                $q->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhereHas('category', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc');

        $news = $query->paginate(10)->withQueryString();
        return view('admin.news.index', compact('news'));
    }

    // Haberlerde arama (admin autocomplete/search)
    public function search(Request $request)
    {
        $search = $request->get('search');
        if (empty($search)) {
            return response()->json(['items' => []], 200);
        }

        $items = News::query()
            ->with('category')
            ->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($news) {
                return [
                    'id' => $news->id,
                    'text' => $news->title,
                    'category' => $news->category ? $news->category->name : null
                ];
            });

        return response()->json(['items' => $items], 200);
    }

    // Haber detayını göster (admin)
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    // Haber oluşturma formu (admin)
    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

    // Haber kaydetme (admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'required|boolean',
            'new_image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240'
        ]);

        // SLUG oluşturma ve benzersizleştirme
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $image_id = null;
        if ($request->hasFile('new_image')) {
            $imageFile = $request->file('new_image');
            $imagePath = $imageFile->store('news-images', 'public');
            $image = Image::create([
                'path' => $imagePath,
                'name' => $imageFile->getClientOriginalName(), // << name alanı DOLDURULUYOR
            ]);
            $image_id = $image->id;
        }

        News::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'content'     => $validated['content'],
            'category_id' => $validated['category_id'],
            'image_id'    => $image_id,
            'is_active'   => $validated['is_active'],
            'user_id'     => auth()->id(),
            'views'       => 0,
            'slug'        => $slug,
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla oluşturuldu.');
    }

    // Haber düzenleme formu (admin)
    public function edit(News $news)
    {
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    // Haber güncelleme (admin)
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'required|boolean',
            'new_image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240'
        ]);

        // Eğer başlık değişirse yeni slug üret, yine benzersiz yap!
        if ($news->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;
            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        // YENİ RESİM yüklenmişse, eskisini sil ve yeni resmi yükle
        if ($request->hasFile('new_image')) {
            // ESKİ RESİM SİLME
            if ($news->image) {
                $oldPath = storage_path('app/public/' . $news->image->path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
                $news->image->delete();
            }

            // YENİ RESMİ KAYDET
            $imageFile = $request->file('new_image');
            $imagePath = $imageFile->store('news-images', 'public');
            $image = Image::create([
                'path' => $imagePath,
                'name' => $imageFile->getClientOriginalName(), // << name alanı DOLDURULUYOR
            ]);
            $validated['image_id'] = $image->id;
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla güncellendi ve görsel güncellendi.');
    }

    // Haber silme (admin)
    public function destroy(News $news)
    {
        // Haber silinirken görseli de sil
        if ($news->image) {
            $oldPath = storage_path('app/public/' . $news->image->path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $news->image->delete();
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber ve görsel başarıyla silindi.');
    }
}
