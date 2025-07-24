<?php

namespace App\Http\Controllers;

use App\Services\MainCategoryService;
use Illuminate\Http\Request;

class MainCategoryController extends Controller
{
    protected $mainCategoryService;

    public function __construct(MainCategoryService $mainCategoryService)
    {
        $this->mainCategoryService = $mainCategoryService;
    }

    public function getMainCategories(int $id = 0)
    {
        try {
            $main_category = $this->mainCategoryService->get($id);

            return $this->successResponse('Ana kategoriler başarıyla getirildi.', $main_category);
        } catch (\Exception $e) {
            return $this->errorResponse('Ana kategoriler getirilirken bir hata oluştu.', $e->getMessage());
        }
    }

    public function createMainCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:main_categories,slug',
            'image_id' => 'nullable|integer|exists:images,id',
        ]);

        try {
            $main_category = $this->mainCategoryService->create($request->only(['name', 'slug', 'image_id']));
            return $this->successResponse('Ana kategori başarıyla oluşturuldu.', $main_category);
        } catch (\Exception $e) {
            return $this->errorResponse('Ana kategori oluşturulurken bir hata oluştu.', $e->getMessage());
        }
    }

    public function updateMainCategory(Request $request, int $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:main_categories,slug,' . $id,
            'image_id' => 'nullable|integer|exists:images,id',
        ]);

        try {
            $updateData = [];
            if ($request->filled('name'))  $updateData['name'] = $request->name;
            if ($request->filled('slug'))  $updateData['slug'] = $request->slug;
            if ($request->has('image_id')) $updateData['image_id'] = $request->image_id;

            $main_category = $this->mainCategoryService->update($id, $updateData);
            return $this->successResponse('Ana kategori başarıyla güncellendi.', $main_category);
        } catch (\Exception $e) {
            return $this->errorResponse('Ana kategori güncellenirken bir hata oluştu.', $e->getMessage());
        }
    }

    public function updateImage(\App\Http\Requests\ImageIdRequest $request)
    {
        $request->validate([
            'image_id' => 'required|integer|exists:images,id',
            'id' => 'required|integer|exists:main_categories,id',
        ]);

        try {
            $main_category = $this->mainCategoryService->updateImage($request->id, $request->image_id);
            return $this->successResponse('Ana kategori resmi başarıyla güncellendi.', $main_category);
        } catch (\Exception $e) {
            return $this->errorResponse('Ana kategori resmi güncellenirken bir hata oluştu.', $e->getMessage());
        }
    }

    public function deleteMainCategory(int $id)
    {
        try {
            $this->mainCategoryService->delete($id);
            return $this->successResponse('Ana kategori başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->errorResponse('Ana kategori silinirken bir hata oluştu.', $e->getMessage());
        }
    }
}
