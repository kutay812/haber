<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']);
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20',
            'content'     => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'nullable|boolean',
            'new_image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'tags'        => 'nullable|string',
        ];
    }
}
