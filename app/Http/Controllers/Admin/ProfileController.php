<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Admin profilini göster
    public function show()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    // Admin profilini güncelle
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'new_password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'current_password'=> ['required_with:new_password'],
        ], [
            'name.required'                  => 'Ad Soyad alanı zorunludur.',
            'email.required'                 => 'E-mail alanı zorunludur.',
            'email.unique'                   => 'Bu e-mail adresi zaten kullanılıyor.',
            'profile_image.image'            => 'Profil resmi bir resim dosyası olmalıdır.',
            'profile_image.mimes'            => 'Profil resmi jpeg, png, jpg veya gif formatında olmalıdır.',
            'profile_image.max'              => 'Profil resmi dosyası en fazla 10MB olabilir.',
            'current_password.required_with' => 'Şifre değiştirmek için mevcut şifreyi girmeniz gerekir.',
            'new_password.min'               => 'Şifre en az 8 karakter olmalıdır.',
            'new_password.confirmed'         => 'Yeni şifre tekrarı eşleşmiyor.',
        ]);

        // Şifre değişikliği istenirse önce mevcut şifre kontrolü
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mevcut şifre yanlış.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        // Profil resmi yüklenmişse işle
        if ($request->hasFile('profile_image')) {
            // Eski resmi sil (profile-image/xxx.jpg formatı!)
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // Yeni resmi "profile-image/" klasörüne kaydet ve tam path olarak ata
            $path = $request->file('profile_image')->store('profile-image', 'public');
            $user->profile_image = $path;
        }

        // Diğer alanlar
        $user->name = $request->name;
        $user->email = $request->email;

        // E-posta değiştiyse doğrulama sıfırlansın
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Profil başarıyla güncellendi.');
    }
}
