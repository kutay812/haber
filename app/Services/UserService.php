<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id, array $data)
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser($id)
    {
        if ($id == Auth::id()) {
            throw new \Exception('Kendi hesabınızı silemezsiniz.');
        }
        return $this->userRepository->delete($id);
    }

    public function profileEdit($user, $data)
    {
        // Only verify current password if user is trying to change password
        if (!empty($data['password']) || !empty($data['current_password'])) {
            if (empty($data['current_password']) || !Hash::check($data['current_password'], $user->password)) {
                throw new \Exception('Mevcut şifreniz yanlış veya girilmedi.');
            }
            $user->password = Hash::make($data['password']);
        }

        $user->name = $data['name'] ?? $user->name;
        $user->email = $data['email'] ?? $user->email;

        if (isset($data['profile_image']) && $data['profile_image']) {
            // Profil resmi güncelle
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $data['profile_image']->store('profile-image', 'public');
        }

        $user->save();
        return $user;
    }
}
