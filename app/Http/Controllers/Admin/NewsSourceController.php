<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsSource;
use App\Models\Category;
use App\Services\RssFetcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsSourceController extends Controller
{
    public function index()
    {
        $sources = NewsSource::with('defaultCategory')->orderBy('name')->paginate(10);
        return view('admin.sources.index', compact('sources'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.sources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                   => 'required|string|max:150',
            'url'                    => 'required|url|max:500',
            'type'                   => 'required|in:rss,api,scraper',
            'website_url'            => 'nullable|url|max:500',
            'logo_url'               => 'nullable|url|max:500',
            'reliability_score'      => 'required|integer|between:0,100',
            'default_category_id'    => 'nullable|exists:categories,id',
            'is_active'              => 'required|boolean',
            'auto_publish'           => 'required|boolean',
            'fetch_interval_minutes' => 'required|integer|min:5',
            'notes'                  => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        NewsSource::create($validated);

        return redirect()->route('admin.sources.index')
            ->with('success', 'Haber kaynağı başarıyla eklendi.');
    }

    public function edit(NewsSource $source)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.sources.edit', compact('source', 'categories'));
    }

    public function update(Request $request, NewsSource $source)
    {
        $validated = $request->validate([
            'name'                   => 'required|string|max:150',
            'url'                    => 'required|url|max:500',
            'type'                   => 'required|in:rss,api,scraper',
            'website_url'            => 'nullable|url|max:500',
            'logo_url'               => 'nullable|url|max:500',
            'reliability_score'      => 'required|integer|between:0,100',
            'default_category_id'    => 'nullable|exists:categories,id',
            'is_active'              => 'required|boolean',
            'auto_publish'           => 'required|boolean',
            'fetch_interval_minutes' => 'required|integer|min:5',
            'notes'                  => 'nullable|string',
        ]);

        $source->update($validated);

        return redirect()->route('admin.sources.index')
            ->with('success', 'Haber kaynağı başarıyla güncellendi.');
    }

    public function destroy(NewsSource $source)
    {
        $source->delete();

        return redirect()->route('admin.sources.index')
            ->with('success', 'Haber kaynağı başarıyla silindi.');
    }

    /**
     * Trigger manual fetch for this specific source
     */
    public function fetch(NewsSource $source, RssFetcherService $fetcher)
    {
        $result = $fetcher->fetchFromSource($source);

        if (isset($result['error'])) {
            return redirect()->back()->with('error', "Haberler çekilemedi: {$result['error']}");
        }

        return redirect()->back()->with('success', "İşlem tamamlandı! Toplam: {$result['total']}, Yeni: {$result['new']}, Atlandı: {$result['skipped']}, Hatalı: {$result['errors']}");
    }
}
