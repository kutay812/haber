<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use App\Http\Requests\ImageIdRequest;
use App\Http\Requests\ImageNameRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function create_temp_url(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'seconds' => 'required|integer|min:1|max:86400',
        ]);
        $result = $this->imageService->createTempUrl($request->filename, $request->seconds);
        if ($result['success']) {
            return response()->json([
                'status' => true,
                'url' => $result['url'],
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => $result['message'] ?? 'Could not create temporary URL',
        ], $result['status'] ?? 500);
    }

    public function download(Request $request, $path = null)
    {
        $filePath = $path ?? $request->path;
        $result = $this->imageService->download($filePath, $request->hasValidSignature());
        if (!$result['success']) {
            abort($result['code'], $result['code'] == 403 ? 'Invalid or expired signature' : 'File not found');
        }
        return $result['file'];
    }

    public function upload(UploadRequest $request)
    {
        $result = $this->imageService->upload($request->file('file'), $request->name);
        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['message'],
            ], $result['code']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Image uploaded successfully',
            'path' => $result['path'],
            'data' => $result['image'],
        ]);
    }

    public function find_by_name(ImageNameRequest $request)
    {
        $images = $this->imageService->findByName($request->name);
        if ($images->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No images found matching your search',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Images found',
            'data' => $images,
        ]);
    }

    public function delete(ImageIdRequest $request)
    {
        $result = $this->imageService->delete($request->id);
        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['message'],
            ], $result['code']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }

    public function update_image(ImageIdRequest $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
        ]);
        $result = $this->imageService->updateImage($request->id, $request->file('file'));
        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['message'],
            ], $result['code']);
        }
        return response()->json([
            'status' => true,
            'message' => 'Image updated successfully',
            'path' => $result['path'],
        ]);
    }
}
