<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\DeleteCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function get_categories(int $id = 0)
    {
        if ($id === 0) {
            $categories = $this->categoryService->getAllCategories();
        } else {
            $categories = $this->categoryService->getCategoryById($id);
        }
        return response()->json(['data' => $categories]);
    }

    public function create_category(CreateCategoryRequest $request)
    {
        try {
            $data = $request->only(['name', 'main_category_id', 'image_id', 'slug', 'title', 'description']);

            // Slug boşsa otomatik oluştur!
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Aynı slug varsa rakam ekle
            $originalSlug = $data['slug'];
            $counter = 1;
            while ($this->categoryService->slugExists($data['slug'])) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }

            $category = $this->categoryService->createCategory($data);

            return response()->json([
                'status' => true,
                'message' => 'Kategori başarıyla oluşturuldu.',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori oluşturulurken bir hata oluştu.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update_category(UpdateCategoryRequest $request, int $id)
    {
        $data = $request->only(['name', 'main_category_id', 'image_id', 'slug', 'title', 'description']);

        // Slug boşsa otomatik oluştur!
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = $this->categoryService->updateCategory($id, $data);
        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori bulunamadı.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Kategori başarıyla güncellendi.',
            'data' => $category
        ]);
    }

    public function delete_category(DeleteCategoryRequest $request, int $id)
    {
        $deleted = $this->categoryService->deleteCategory($id);
        if (!$deleted) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori bulunamadı.'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Kategori başarıyla silindi.'
        ]);
    }
}
