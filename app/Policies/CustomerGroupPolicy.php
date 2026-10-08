<?php

namespace App\Policies;

use App\Models\CustomerGroup;
use App\Models\User;

class CustomerGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('customer_groups.view');
    }

    public function view(User $user, CustomerGroup $customerGroup): bool
    {
        return $user->hasPermission('customer_groups.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('customer_groups.create');
    }

    public function update(User $user, CustomerGroup $customerGroup): bool
    {
        return $user->hasPermission('customer_groups.update');
    }

    public function delete(User $user, CustomerGroup $customerGroup): bool
    {
        return $user->hasPermission('customer_groups.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('customer_groups.trash.view');
    }

    public function restore(User $user, CustomerGroup $customerGroup): bool
    {
        return $user->hasPermission('customer_groups.trash.restore');
    }

    public function forceDelete(User $user, CustomerGroup $customerGroup): bool
    {
        return $user->hasPermission('customer_groups.trash.force_delete');
    }
}
