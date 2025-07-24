<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->withCount('news')
            ->when($request->filled('search'), function ($q) use ($request) {
                $searchTerm = $request->search;
                $q->where('name', 'like', "%{$searchTerm}%");
            })
            ->orderBy('name');

        $categories = $query->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $mainCategories = Category::whereNull('main_category_id')->get();
        $images = Image::all();
        return view('admin.categories.create', compact('mainCategories', 'images'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'main_category_id' => 'nullable|exists:categories,id',
            'image_id' => 'nullable|exists:images,id',
        ]);

        // SLUG OLUŞTURMA ve UNİQUE YAPMA
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $category = new Category($validated);
        $category->slug = $slug;
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function edit(Category $category)
    {
        $mainCategories = Category::whereNull('main_category_id')->where('id', '!=', $category->id)->get();
        $images = Image::all();
        return view('admin.categories.edit', compact('category', 'mainCategories', 'images'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'main_category_id' => 'nullable|exists:categories,id',
            'image_id' => 'nullable|exists:images,id',
        ]);

        // Eğer isim değişmişse slug da değişsin
        if ($validated['name'] !== $category->name) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(Category $category)
    {
        if ($category->news()->exists()) {
            return back()->with('error', 'Bu kategoriye ait haberler olduğu için silinemez.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }

    public function search(Request $request)
    {
        try {
            $search = $request->get('search');

            if (empty($search)) {
                return response()->json(['items' => []], 200);
            }

            $items = Category::query()
                ->withCount('news')
                ->where('name', 'like', "%{$search}%")
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name,
                        'category' => $category->news_count . ' haber'
                    ];
                });

            return response()->json(['items' => $items], 200);

        } catch (\Exception $e) {
            \Log::error('Kategori arama hatası: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Arama işlemi sırasında bir hata oluştu'
            ], 500);
        }
    }
}
