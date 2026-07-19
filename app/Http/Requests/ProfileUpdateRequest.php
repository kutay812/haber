<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();
        return [
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:users,email,' . $userId,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Ad Soyad alanı zorunludur.',
            'email.required'      => 'E-posta alanı zorunludur.',
            'email.email'         => 'Geçerli bir e-posta adresi girin.',
            'email.unique'        => 'Bu e-posta adresi başka bir kullanıcı tarafından kullanılıyor.',
            'profile_image.image' => 'Profil fotoğrafı geçerli bir görsel olmalıdır.',
            'profile_image.mimes' => 'Profil fotoğrafı formatı JPG, JPEG, PNG veya WEBP olmalıdır.',
            'profile_image.max'   => 'Profil fotoğrafı boyutu en fazla 2MB olabilir.',
        ];
    }
}
