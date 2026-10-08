<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('customers.view');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('customers.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.update');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('customers.trash.view');
    }

    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.trash.restore');
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customers.trash.force_delete');
    }
}
