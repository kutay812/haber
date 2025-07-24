<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function assignRoleToUser($user_id, $role_name)
    {
        $user = User::findOrFail($user_id);
        $user->syncRoles([$role_name]);
        return $user;
    }

    public function removeRoleFromUser($user_id, $role_name)
    {
        $user = User::findOrFail($user_id);
        $user->removeRole($role_name);
        return $user;
    }

    public function getUserRoles($user_id)
    {
        $user = User::findOrFail($user_id);
        return [
            'user' => $user->name,
            'roles' => $user->roles
        ];
    }
}
