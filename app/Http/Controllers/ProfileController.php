<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function show()
    {
        $user = Auth::user();
        $roles = method_exists($user, 'roles') ? $user->roles->pluck('name')->toArray() : ['Kullanıcı'];
        return view('profile', compact('user', 'roles'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        
        // Delegate to UserService (note that UserService->profileEdit is the repository implementation,
        // but we can directly update attributes cleanly or use profileEdit.
        // Let's use direct updating properties or call profileEdit depending on format)
        
        $this->userService->profileEdit($user, $request->validated());

        return redirect('/')->with('success', 'Profiliniz başarıyla güncellendi.');
    }
}
