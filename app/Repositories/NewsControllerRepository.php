<?php

namespace App\Repositories;

use App\Models\News;

class NewsControllerRepository implements NewsControllerRepositoryInterface
{
    public function all()
    {
        return News::all();
    }

    public function find($id)
    {
        return News::find($id);
    }

    public function findBySlug($slug)
    {
        return News::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        return News::create($data);
    }

    public function update($id, array $data)
    {
        $news = News::find($id);
        if (!$news) return null;
        $news->update($data);
        return $news;
    }

    public function delete($id)
    {
        $news = News::find($id);
        if (!$news) return false;
        return $news->delete();
    }
}
