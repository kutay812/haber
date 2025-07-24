<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;

class NewsController extends Controller
{
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

    public function search(Request $request)
    {
        try {
            \Log::info('Arama isteği alındı', ['search' => $request->get('search')]);
            
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

            \Log::info('Arama sonuçları', ['count' => $items->count()]);
            return response()->json(['items' => $items], 200);

        } catch (\Exception $e) {
            \Log::error('Haber arama hatası: ' . $e->getMessage(), [
                'exception' => $e,
                'search' => $request->get('search')
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Arama işlemi sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        $images = Image::all();
        return view('admin.news.create', compact('categories', 'images'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image_id' => 'nullable|exists:images,id',
            'is_active' => 'required|boolean'
        ]);

        $news = News::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'image_id' => $validated['image_id'] ?? null,
            'is_active' => $validated['is_active'],
            'user_id' => auth()->id(),
            'views' => 0
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla oluşturuldu.');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $images = Image::all();
        return view('admin.news.edit', compact('news', 'categories', 'images'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image_id' => 'nullable|exists:images,id',
            'is_active' => 'required|boolean'
        ]);

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla güncellendi.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla silindi.');
    }
}