<?php

namespace App\Repositories;

use App\Models\MainCategory;

class MainCategoryRepository implements MainCategoryRepositoryInterface
{
    public function all()
    {
        return MainCategory::all();
    }

    public function find($id)
    {
        return MainCategory::find($id);
    }

    public function create(array $data)
    {
        return MainCategory::create($data);
    }

    public function update($id, array $data)
    {
        $mainCategory = MainCategory::find($id);
        if (!$mainCategory) return null;
        $mainCategory->update($data);
        return $mainCategory;
    }

    public function delete($id)
    {
        $mainCategory = MainCategory::find($id);
        if (!$mainCategory) return false;
        return $mainCategory->delete();
    }
}
