<?php

namespace App\Services;

use App\Repositories\ImageRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $imageRepository;
    protected $disk_name = 'public';

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function createTempUrl($filename, $seconds)
    {
        $disk = Storage::disk($this->disk_name);

        if (!$disk->exists($filename)) {
            return [ 'success' => false, 'message' => 'File not found', 'status' => 404 ];
        }

        $url = $disk->temporaryUrl($filename, now()->addSeconds($seconds));
        return [ 'success' => true, 'url' => $url ];
    }

    public function download($filePath, $hasValidSignature)
    {
        $disk = Storage::disk($this->disk_name);

        if (!$disk->exists($filePath)) {
            return [ 'success' => false, 'code' => 404 ];
        }
        if (!$hasValidSignature) {
            return [ 'success' => false, 'code' => 403 ];
        }

        return [ 'success' => true, 'file' => $disk->download($filePath) ];
    }

    public function upload($file, $name)
    {
        $disk = Storage::disk($this->disk_name);
        $maxSize = 10 * 1024 * 1024; // 10MB
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if ($file->getSize() > $maxSize) {
            return [ 'success' => false, 'message' => 'File size too large. Maximum 10MB allowed.', 'code' => 413 ];
        }
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return [ 'success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.', 'code' => 422 ];
        }

        $year = now()->format('Y');
        $month = now()->format('m');
        $path = $file->store("uploads/images/$year/$month", $this->disk_name);

        $image = $this->imageRepository->create([
            'name' => $name,
            'path' => $path,
        ]);
        return [ 'success' => true, 'image' => $image, 'path' => $path ];
    }

    public function findByName($name)
    {
        return $this->imageRepository->findByName($name);
    }

    public function delete($id)
    {
        $disk = Storage::disk($this->disk_name);
        $image = $this->imageRepository->find($id);
        if (!$image) {
            return [ 'success' => false, 'message' => 'Image not found', 'code' => 404 ];
        }
        if ($disk->exists($image->path)) {
            $disk->delete($image->path);
        }
        $image->delete();
        return [ 'success' => true ];
    }

    public function updateImage($id, $file)
    {
        $disk = Storage::disk($this->disk_name);
        $image = $this->imageRepository->find($id);
        if (!$image) {
            return [ 'success' => false, 'message' => 'Image not found', 'code' => 404 ];
        }

        $oldPath = $image->path;
        $year = now()->format('Y');
        $month = now()->format('m');
        $newPath = $file->store("uploads/images/$year/$month", $this->disk_name);

        $this->imageRepository->update($id, ['path' => $newPath]);

        if ($oldPath && $disk->exists($oldPath)) {
            $disk->delete($oldPath);
        }

        return [ 'success' => true, 'path' => $newPath ];
    }
}
