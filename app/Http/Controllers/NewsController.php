<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->middleware('permission:news.view', ['only' => ['index', 'show']]);
        $this->middleware('permission:news.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:news.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news.delete', ['only' => ['destroy']]);
        $this->newsService = $newsService;
    }

    public function index()
    {
        // Eğer haberleri listelemek istiyorsan:
        // $news = $this->newsService->getAll();
        // return view('admin.news.index', compact('news'));
        return view('admin.news.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'image_id' => 'required|exists:images,id',
            'category_id' => 'required|exists:categories,id'
        ]);

        $this->newsService->create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $news = $this->newsService->getById($id);
        return view('admin.news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = $this->newsService->getById($id);
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'image_id' => 'required|exists:images,id',
            'category_id' => 'required|exists:categories,id'
        ]);

        $this->newsService->update($id, $validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('news.delete')) {
            return redirect()->route('admin.news.index')
                ->with('error', 'Haber silme yetkiniz bulunmamaktadır.');
        }

        $this->newsService->delete($id);

        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla silindi.');
    }
}
