<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Tüm kullanıcıları göster (view)
    public function view_all_users(Request $request): View
    {
        $users = $this->userService->getAllUsers();
        return view('admin.users.index', compact('users'));
    }

    // Kullanıcı oluştur (API)
    public function create_user(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        try {
            $user = $this->userService->createUser($validated);
            return response()->json([
                'status' => true,
                'message' => 'Kullanıcı başarıyla oluşturuldu.',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kullanıcı oluşturulurken bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Kullanıcı sil (API)
    public function delete_user(int $user_id): JsonResponse
    {
        try {
            $this->userService->deleteUser($user_id);
            return response()->json([
                'status' => true,
                'message' => 'Kullanıcı başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Kullanıcı güncelle (API)
    public function update_user(Request $request, int $user_id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user_id,
            'password' => 'nullable|min:8|confirmed'
        ]);

        try {
            $user = $this->userService->updateUser($user_id, $validated);
            return response()->json([
                'status' => true,
                'message' => 'Kullanıcı başarıyla güncellendi.',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ID ile kullanıcıyı getir (API)
    public function get_user_from_id(int $user_id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($user_id);
            return response()->json([
                'status' => true,
                'message' => 'Kullanıcı bilgileri başarıyla getirildi.',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Kullanıcı bulunamadı.'
            ], 404);
        }
    }

    // Giriş yapmış kullanıcıyı getir (API)
    public function get_user(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Kullanıcı bulunamadı.'
            ], 401);
        }
        return response()->json([
            'status' => true,
            'message' => 'Kullanıcı bilgileri başarıyla getirildi.',
            'data' => $user
        ]);
    }

    // Profil düzenle (web form)
    public function profileEdit(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        try {
            $this->userService->profileEdit($user, $validated);
            return back()->with('success', 'Profil başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
