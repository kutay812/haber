<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Kullanıcı kendi profilini gösterir
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }

    // Kullanıcı kendi profilini günceller
    public function update_profile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
            // ek: kendi şifresini değiştirmek isterse
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user = $this->userService->updateUser(Auth::id(), $validated);
        return response()->json([
            'status' => true,
            'message' => 'Profil güncellendi.',
            'user' => $user
        ]);
    }

    // Kullanıcı kayıt olur
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
        $user = $this->userService->createUser($validated);
        // İstersen burada otomatik login de yapabilirsin
        return response()->json([
            'status' => true,
            'message' => 'Kayıt başarılı!',
            'user' => $user
        ], 201);
    }

    // (Eğer gerekiyorsa) sadece kendi hesabını siler
    public function delete_profile(): JsonResponse
    {
        $this->userService->deleteUser(Auth::id());
        Auth::logout();
        return response()->json(['status' => true, 'message' => 'Hesabınız silindi.']);
    }
}
