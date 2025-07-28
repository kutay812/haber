<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $roles = method_exists($user, 'roles') ? $user->roles->pluck('name')->toArray() : ['Kullanıcı'];
        return view('profile', compact('user', 'roles'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Profil resmi yüklemesi (profile-image klasörüne)
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile-image', 'public');
            $user->profile_image = $path;
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('/')->with('success', 'Profiliniz başarıyla güncellendi.');
    }
}
