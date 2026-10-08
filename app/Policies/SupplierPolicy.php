<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('suppliers.view');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('suppliers.create');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers.update');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('suppliers.trash.view');
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers.trash.restore');
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->hasPermission('suppliers.trash.force_delete');
    }
}
