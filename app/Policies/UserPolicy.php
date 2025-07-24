<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasRole('Admin');
    }

    public function view(User $user, User $model)
    {
        return $user->hasRole('Admin');
    }

    public function create(User $user)
    {
        return $user->hasRole('Admin');
    }

    public function update(User $user, User $model)
    {
        // Admin kendini veya diğer kullanıcıları güncelleyebilir
        return $user->hasRole('Admin');
    }

    public function delete(User $user, User $model)
    {
        // Admin kendisi dışındaki kullanıcıları silebilir
        return $user->hasRole('Admin') && $user->id !== $model->id;
    }
} 