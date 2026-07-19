<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Ad Soyad alanı zorunludur.',
            'email.required'     => 'E-posta alanı zorunludur.',
            'email.email'        => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.unique'       => 'Bu e-posta adresi zaten kullanımda.',
            'password.required'  => 'Şifre alanı zorunludur.',
            'password.min'       => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ];
    }
}
