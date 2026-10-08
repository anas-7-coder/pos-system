<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('brands.view');
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->hasPermission('brands.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('brands.create');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasPermission('brands.update');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasPermission('brands.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('brands.trash.view');
    }

    public function restore(User $user, Brand $brand): bool
    {
        return $user->hasPermission('brands.restore');
    }

    public function forceDelete(User $user, Brand $brand): bool
    {
        return $user->hasPermission('brands.force_delete');
    }
}
