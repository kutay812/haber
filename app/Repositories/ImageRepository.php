<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository implements ImageRepositoryInterface
{
    public function find($id)
    {
        return Image::find($id);
    }

    public function findByName($name)
    {
        return Image::where('name', 'like', "%{$name}%")
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function create(array $data)
    {
        return Image::create($data);
    }

    public function update($id, array $data)
    {
        $image = Image::find($id);
        if (!$image) return null;
        $image->update($data);
        return $image;
    }

    public function delete($id)
    {
        $image = Image::find($id);
        if (!$image) return false;
        return $image->delete();
    }
}
