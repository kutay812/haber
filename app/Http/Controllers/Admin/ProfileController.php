<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasyon kuralları
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'current_password' => ['required_with:new_password'],
        ], [
            'name.required' => 'Ad Soyad alanı zorunludur.',
            'email.required' => 'E-mail alanı zorunludur.',
            'email.unique' => 'Bu e-mail adresi zaten kullanılıyor.',
            'profile_image.image' => 'Profil resmi bir resim dosyası olmalıdır.',
            'profile_image.mimes' => 'Profil resmi jpeg, png, jpg veya gif formatında olmalıdır.',
            'profile_image.max' => 'Profil resmi dosyası en fazla 10MB olabilir.',
            'current_password.required_with' => 'Şifre değiştirmek için mevcut şifreyi girmeniz gerekir.',
            'new_password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'new_password.confirmed' => 'Yeni şifre tekrarı eşleşmiyor.',
        ]);

        // Şifre değişikliği isteniyorsa önce mevcut şifreyi kontrol et
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Mevcut şifre yanlış.'])
                    ->withInput();
            }
            // Yeni şifreyi güncelle
            $user->password = Hash::make($request->new_password);
        }

        // Profil resmi yüklendiyse işle
        if ($request->hasFile('profile_image')) {
            // Önceki resmi sil (varsa)
            if ($user->profile_image && Storage::disk('public')->exists('profile-image/' . $user->profile_image)) {
                Storage::disk('public')->delete('profile-image/' . $user->profile_image);
            }
            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile-image', $filename, 'public');
            $user->profile_image = $filename;
        }

        // Diğer alanları güncelle
        $user->name = $request->name;
        $user->email = $request->email;

        // Eğer e-posta değiştiyse doğrulama sıfırlansın (isteğe bağlı)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Profil başarıyla güncellendi.');
    }
}
