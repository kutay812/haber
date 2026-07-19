<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * User registration action
     */
    public function register(RegisterRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Default User role - Create if it doesn't exist to prevent crash
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'User']);
            $user->assignRole($role);
        });

        return redirect('/')->with('register_success', 'Kayıt başarılı! Giriş yapabilirsiniz.');
    }
}
