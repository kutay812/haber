<?php

namespace App\Services;

use App\Repositories\MainCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MainCategoryService
{
    protected $mainCategoryRepository;

    public function __construct(MainCategoryRepositoryInterface $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }

    public function get($id = 0)
    {
        if ($id) {
            $mainCategory = $this->mainCategoryRepository->find($id);
            if (!$mainCategory) {
                throw new \Exception('Ana kategori bulunamadı.');
            }
            return $mainCategory;
        }
        return $this->mainCategoryRepository->all();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $mainCategory = $this->mainCategoryRepository->create($data);
            DB::commit();
            return $mainCategory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $mainCategory = $this->mainCategoryRepository->find($id);
            if (!$mainCategory) throw new \Exception('Ana kategori bulunamadı.');
            $updated = $this->mainCategoryRepository->update($id, $data);
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateImage($id, $image_id)
    {
        DB::beginTransaction();
        try {
            $mainCategory = $this->mainCategoryRepository->find($id);
            if (!$mainCategory) throw new \Exception('Ana kategori bulunamadı.');
            $updated = $this->mainCategoryRepository->update($id, ['image_id' => $image_id]);
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $mainCategory = $this->mainCategoryRepository->find($id);
            if (!$mainCategory) throw new \Exception('Ana kategori bulunamadı.');
            $this->mainCategoryRepository->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
