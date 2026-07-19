<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Haberleri listele (admin tablosu)
     */
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

    /**
     * Haberlerde arama (admin autocomplete/search)
     */
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

    /**
     * Haber detayını göster (admin)
     */
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /**
     * Haber oluşturma formu (admin)
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Haber kaydetme (admin)
     */
    public function store(StoreNewsRequest $request)
    {
        $this->newsService->createNews($request->validated(), auth()->id());

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla oluşturuldu.');
    }

    /**
     * Haber düzenleme formu (admin)
     */
    public function edit(News $news)
    {
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Haber güncelleme (admin)
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->newsService->updateNews($news, $request->validated());

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla güncellendi.');
    }

    /**
     * Haber silme (admin)
     */
    public function destroy(News $news)
    {
        $this->newsService->deleteNews($news);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla silindi.');
    }
}
