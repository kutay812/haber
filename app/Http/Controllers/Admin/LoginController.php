<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Admin login formu
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Admin login işlemi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [], [
            'email'    => 'E-posta',
            'password' => 'Şifre'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Kullanıcı rol kontrolü (sadece yetkili roller girebilsin)
            $user = Auth::user();
            if (!$user->hasAnyRole(['Super Admin', 'Admin', 'Editor'])) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'İzniniz yok. Admin paneline erişemezsiniz.'
                ])->onlyInput('email');
            }

            return redirect('/admin');
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler hatalı.',
        ])->onlyInput('email');
    }

    // Admin logout işlemi
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
