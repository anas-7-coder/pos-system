<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('categories.view');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->hasPermission('categories.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('categories.create');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermission('categories.update');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermission('categories.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('categories.trash.view');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->hasPermission('categories.restore');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasPermission('categories.force_delete');
    }
}
