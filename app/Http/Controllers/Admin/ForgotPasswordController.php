<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Şifre sıfırlama mail formu (admin)
    public function showLinkRequestForm()
    {
        return view('admin.forgot-password');
    }

    // Şifre sıfırlama maili gönder (admin)
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [], ['email' => 'E-posta']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Yeni şifre formu (admin)
    public function showResetForm(Request $request, $token)
    {
        return view('admin.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Şifreyi güncelle (admin)
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [], [
            'email'    => 'E-posta',
            'password' => 'Şifre',
            'password_confirmation' => 'Şifre (Tekrar)'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/admin/login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
