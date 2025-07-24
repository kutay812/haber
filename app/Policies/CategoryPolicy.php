<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Category $category)
    {
        return $user->hasRole('admin');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('admin');
    }
} 