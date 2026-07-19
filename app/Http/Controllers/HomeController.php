<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use App\Services\MarketDataService;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * ANA SAYFA
     */
    public function index(Request $request, MarketDataService $marketDataService)
    {
        $categories = Cache::remember('categories.all', 300, function () {
            return Category::orderBy('name')->get();
        });

        // ── KATEGORİ VE ARAMA FİLTRESİ ──
        $categorySlug = $request->query('kategori') ?? $request->route('slug');
        $category = null;
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
        }

        // ── PİYASA VERİLERİ (DİNAMİK) ──
        $marketData = $marketDataService->getMarketData();

        // ── MANŞET HABERLERİ ──
        $featuredNews = Cache::remember('news.featured.' . ($categorySlug ?? 'all'), 120, function () use ($category) {
            $query = News::with(['category', 'user', 'image', 'tags', 'newsSource'])->featured()->latest();
            if ($category) $query->where('category_id', $category->id);
            $results = $query->limit(10)->get(); 

            // Eğer manşet yoksa (hepsi silinmişse), en yeni haberleri manşet yap (YEDEK SİSTEM)
            if ($results->isEmpty()) {
                $fallbackQuery = News::with(['category', 'user', 'image', 'tags', 'newsSource'])->published()->latest();
                if ($category) $fallbackQuery->where('category_id', $category->id);
                $results = $fallbackQuery->limit(10)->get();
            }
            return $results;
        });

        // ── SON DAKİKA ──
        $breakingNews = Cache::remember('news.breaking', 60, function () {
            $results = News::with('category')->breaking()->latest()->limit(10)->get();
            if ($results->isEmpty()) {
                $results = News::with('category')->published()->latest()->limit(10)->get();
            }
            return $results;
        });

        // ── EDİTÖRÜN SEÇTİKLERİ ──
        $editorPicks = Cache::remember('news.editor_picks', 300, function () {
            $results = News::with(['category', 'user', 'image'])->editorPicks()->latest()->limit(5)->get();
            if ($results->isEmpty()) {
                $results = News::with(['category', 'user', 'image'])->published()->inRandomOrder()->limit(5)->get();
            }
            return $results;
        });

        // ── EN ÇOK OKUNANLAR (Son 7 gün) ──
        $mostRead = Cache::remember('news.most_read', 300, function () {
            return News::with(['category', 'image'])
                       ->published()->where('created_at', '>=', now()->subDays(7))
                       ->orderByDesc('views')->limit(10)->get();
        });

        // ── KATEGORİ BAZLI HABERLER (Sadece Ana Sayfa için) ──
        $categoryBlocks = [];
        if (!$category && !$request->filled('search') && !$request->filled('tag')) {
            $targetCategories = ['gundem', 'ekonomi', 'spor', 'dunya', 'teknoloji', 'yasam', 'saglik', 'otomobil'];
            
            foreach ($targetCategories as $slug) {
                $categoryBlocks[$slug] = Cache::remember('news.category_block.' . $slug, 180, function () use ($slug) {
                    return News::with(['category', 'image'])
                               ->published()
                               ->whereHas('category', function($q) use ($slug) {
                                   $q->where('slug', $slug);
                               })
                               ->latest()
                               ->limit(6) // 1 Büyük, 5 Küçük vb.
                               ->get();
                });
            }
        }

        // ── TREND ETİKETLER ──
        $trendingTags = Cache::remember('tags.trending', 600, function () {
            return Tag::orderByDesc('usage_count')->limit(15)->get();
        });

        // ── SON HABERLER VEYA ARAMA SONUÇLARI ──
        $newsQuery = News::with(['category', 'user', 'image', 'tags', 'newsSource'])->published()->latest();

        if ($category) {
            $newsQuery->where('category_id', $category->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $newsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tag')) {
            $newsQuery->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $news = $newsQuery->paginate(12);

        $canEditNews = Auth::check() && Auth::user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']);

        return view('welcome', compact(
            'categories', 'news', 'category', 'canEditNews',
            'featuredNews', 'breakingNews', 'editorPicks',
            'editorPicks', 'mostRead', 'trendingTags', 'categoryBlocks', 'marketData'
        ));
    }

    /**
     * HABER DETAY SAYFASI
     */
    public function show($slug)
    {
        $news = News::with(['category', 'user', 'image', 'tags', 'comments.user', 'newsSource'])
                     ->where('slug', $slug)
                     ->firstOrFail();

        // Görüntülenme sayısını artır (session bazlı)
        $sessionKey = 'viewed_news_' . $news->id;
        if (!session()->has($sessionKey)) {
            $news->increment('views');
            session()->put($sessionKey, true);
        }

        // Benzer haberler
        $relatedNews = $news->relatedNews(4);

        // En çok okunanlar (sidebar)
        $mostRead = News::with(['category', 'image'])
                        ->published()
                        ->where('created_at', '>=', now()->subDays(7))
                        ->orderByDesc('views')
                        ->limit(5)
                        ->get();

        // Önceki / Sonraki haber
        $prevNews = News::published()
                        ->where('id', '<', $news->id)
                        ->orderByDesc('id')
                        ->first();

        $nextNews = News::published()
                        ->where('id', '>', $news->id)
                        ->orderBy('id')
                        ->first();

        // Kategorideki diğer haberler
        $categoryNews = News::with(['image'])
                            ->published()
                            ->where('category_id', $news->category_id)
                            ->where('id', '!=', $news->id)
                            ->latest()
                            ->limit(4)
                            ->get();

        return view('news.show', compact(
            'news', 'relatedNews', 'prevNews', 'nextNews', 'categoryNews', 'mostRead'
        ));
    }

    /**
     * HABER KAYDETME (STORE)
     */
    public function store(StoreNewsRequest $request)
    {
        $this->newsService->createNews($request->validated(), auth()->id());
        $this->clearNewsCache();

        return redirect()->route('home')->with('success', 'Haber başarıyla eklendi!');
    }

    /**
     * HABER GÜNCELLEME (UPDATE)
     */
    public function update(UpdateNewsRequest $request, $id)
    {
        $haber = News::findOrFail($id);
        $this->newsService->updateNews($haber, $request->validated());
        $this->clearNewsCache();

        return redirect()->route('news.show', $haber->slug)->with('success', 'Haber başarıyla güncellendi.');
    }

    /**
     * Cache temizle
     */
    protected function clearNewsCache(): void
    {
        Cache::forget('news.featured.all');
        Cache::forget('news.breaking');
        Cache::forget('news.editor_picks');
        Cache::forget('news.most_read');
        Cache::forget('news.most_commented');
        Cache::forget('tags.trending');
    }
}
