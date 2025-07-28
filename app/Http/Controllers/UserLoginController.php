<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller
{
    // Giriş formunu göster
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Giriş işlemi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Hoş geldiniz!');
        }

        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler hatalı.',
        ])->onlyInput('email');
    }

    // Çıkış işlemi
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Anasayfaya yönlendir!
        return redirect('/')->with('success', 'Çıkış yapıldı.');
        // Alternatif: return redirect()->route('home')->with('success', 'Çıkış yapıldı.');
    }
}
