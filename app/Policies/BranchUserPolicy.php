<?php

namespace App\Policies;

use App\Models\User;

class BranchUserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('branches.users.view');
    }

    public function assign(User $user): bool
    {
        return $user->hasPermission('branches.users.assign');
    }

    public function remove(User $user): bool
    {
        return $user->hasPermission('branches.users.remove');
    }
}
