<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Şifre sıfırlama formunu göster
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Şifre sıfırlama linkini email ile gönder
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists' => 'Bu e-posta adresi sistemde kayıtlı değil.'
        ]);

        // Şifre sıfırlama token'ı oluştur
        $token = Str::random(64);
        
        // Token'ı veritabanına kaydet
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Email gönder (basit bir örnek)
        $user = User::where('email', $request->email)->first();
        $resetUrl = route('admin.password.reset', ['token' => $token]) . '?email=' . urlencode($request->email);
        
        // Burada gerçek email gönderme işlemi yapılacak
        // Mail::send() kullanabilirsiniz
        
        return back()->with('status', 'Şifre sıfırlama linki e-posta adresinize gönderildi.');
    }

    /**
     * Şifre sıfırlama formunu göster
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Şifreyi sıfırla
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'E-posta adresi gereklidir.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.exists' => 'Bu e-posta adresi sistemde kayıtlı değil.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
            'token.required' => 'Geçersiz token.'
        ]);

        // Token'ı kontrol et
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Geçersiz token veya e-posta adresi.']);
        }

        // Token'ın süresi geçmiş mi kontrol et (24 saat)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            return back()->withErrors(['email' => 'Şifre sıfırlama linkinin süresi dolmuş.']);
        }

        // Şifreyi güncelle
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Token'ı sil
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('status', 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.');
    }
}