<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;

class WarehousePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('warehouses.view');
    }

    public function view(User $user, Warehouse $warehouse): bool
    {
        return $user->hasPermission('warehouses.view') && $user->canAccessBranch($warehouse->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('warehouses.create');
    }

    public function update(User $user, Warehouse $warehouse): bool
    {
        return $user->hasPermission('warehouses.update') && $user->canAccessBranch($warehouse->branch_id);
    }

    public function delete(User $user, Warehouse $warehouse): bool
    {
        return $user->hasPermission('warehouses.delete') && $user->canAccessBranch($warehouse->branch_id);
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('warehouses.trash.view');
    }

    public function restore(User $user, Warehouse $warehouse): bool
    {
        return $user->hasPermission('warehouses.restore');
    }

    public function forceDelete(User $user, Warehouse $warehouse): bool
    {
        return $user->hasPermission('warehouses.force_delete');
    }
}
