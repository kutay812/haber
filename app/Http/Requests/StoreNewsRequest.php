<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Editor', 'Super Admin']);
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:5|max:200',
            'description' => 'required|string|min:20|max:300',
            'content'     => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'tags'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Haber başlığı zorunludur.',
            'title.min'            => 'Haber başlığı en az 5 karakter olmalıdır.',
            'title.max'            => 'Haber başlığı en fazla 200 karakter olmalıdır.',
            'description.required' => 'Haber özeti (açıklama) zorunludur.',
            'description.min'      => 'Haber özeti en az 20 karakter olmalıdır.',
            'description.max'      => 'Haber özeti en fazla 300 karakter olmalıdır.',
            'content.required'     => 'Haber içeriği zorunludur.',
            'content.min'          => 'Haber içeriği en az 50 karakter olmalıdır.',
            'category_id.required' => 'Lütfen geçerli bir kategori seçin.',
            'category_id.exists'   => 'Seçilen kategori sistemde bulunamadı.',
            'image.image'          => 'Lütfen geçerli bir görsel yükleyin.',
            'image.mimes'          => 'Sadece JPG, JPEG, PNG, GIF ve WEBP formatları desteklenir.',
            'image.max'            => 'Görsel boyutu en fazla 10MB olabilir.',
        ];
    }
}
