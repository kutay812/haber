<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Kullanıcının bu isteği yapmaya yetkili olup olmadığını belirtir.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Geçerli doğrulama kurallarını döner.
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:90',
            'description' => 'nullable|string',
            'title' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,' . $this->route('id'),
            'image_id' => 'nullable|integer|exists:images,id',
        ];
    }

    /**
     * Hata mesajlarını özelleştirir.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Kategori adı metin tipinde olmalıdır.',
            'name.max' => 'Kategori adı en fazla 90 karakter olabilir.',
            'description.string' => 'Açıklama metin tipinde olmalıdır.',
            'title.string' => 'Başlık metin tipinde olmalıdır.',
            'slug.string' => 'Slug metin tipinde olmalıdır.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
            'image_id.integer' => 'Resim ID’si bir tam sayı olmalıdır.',
            'image_id.exists' => 'Belirtilen resim bulunamadı.',
        ];
    }
}
