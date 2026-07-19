<?php

namespace App\Services;

use App\Models\News;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NewsService
{
    /**
     * Create news article with associated tags and image
     */
    public function createNews(array $data, ?int $authorId): News
    {
        return DB::transaction(function () use ($data, $authorId) {
            // Slug generation
            $slug = Str::slug($data['title']);
            $originalSlug = $slug;
            $counter = 2;
            while (News::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            // Image handling
            $imageId = null;
            if (isset($data['image']) && $data['image']->isValid()) {
                $imageId = $this->uploadImage($data['image']);
            } elseif (isset($data['new_image']) && $data['new_image']->isValid()) {
                $imageId = $this->uploadImage($data['new_image']);
            }

            // News creation
            $news = News::create([
                'title'          => $data['title'],
                'description'    => $data['description'],
                'content'        => $data['content'],
                'category_id'    => $data['category_id'],
                'user_id'        => $authorId,
                'is_active'      => $data['is_active'] ?? true,
                'status'         => $data['status'] ?? 'published',
                'slug'           => $slug,
                'image_id'       => $imageId,
                'views'          => 0,
            ]);

            // Tags handling
            if (!empty($data['tags'])) {
                $this->syncTags($news, $data['tags']);
            }

            return $news;
        });
    }

    /**
     * Update news article
     */
    public function updateNews(News $news, array $data): News
    {
        return DB::transaction(function () use ($news, $data) {
            // Re-generate slug if title changed
            if (isset($data['title']) && $news->title !== $data['title']) {
                $slug = Str::slug($data['title']);
                $originalSlug = $slug;
                $counter = 2;
                while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $news->slug = $slug;
            }

            // Handle image updates
            $uploadedImage = $data['image'] ?? $data['new_image'] ?? null;
            if ($uploadedImage && $uploadedImage->isValid()) {
                // Delete old image
                if ($news->image) {
                    Storage::disk('public')->delete($news->image->path);
                    $news->image->delete();
                }

                $imageId = $this->uploadImage($uploadedImage);
                $news->image_id = $imageId;
            }

            // General attributes update
            $news->fill([
                'title'       => $data['title'] ?? $news->title,
                'description' => $data['description'] ?? $news->description,
                'content'     => $data['content'] ?? $news->content,
                'category_id' => $data['category_id'] ?? $news->category_id,
                'is_active'   => isset($data['is_active']) ? (bool) $data['is_active'] : $news->is_active,
                'status'      => $data['status'] ?? $news->status,
            ]);

            $news->save();

            // Tags updates
            if (isset($data['tags'])) {
                $this->syncTags($news, $data['tags']);
            }

            return $news;
        });
    }

    /**
     * Delete news article and its image safely
     */
    public function deleteNews(News $news): void
    {
        DB::transaction(function () use ($news) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image->path);
                $news->image->delete();
            }
            $news->tags()->detach();
            $news->delete();
        });
    }

    /**
     * Private helper to upload image and record it
     */
    private function uploadImage($imageFile): int
    {
        $path = $imageFile->store('news-images', 'public');
        $image = Image::create([
            'path' => $path,
            'name' => $imageFile->getClientOriginalName(),
        ]);
        return $image->id;
    }

    /**
     * Private helper to sync tags
     */
    private function syncTags(News $news, string $tagsString): void
    {
        $tagNames = array_map('trim', explode(',', $tagsString));
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            if (!empty($tagName)) {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tag->increment('usage_count');
                $tagIds[] = $tag->id;
            }
        }
        $news->tags()->sync($tagIds);
    }
}
