<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('branches.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.view') && $user->canAccessBranch($branch->id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('branches.create');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.update') && $user->canAccessBranch($branch->id);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.delete') && $user->canAccessBranch($branch->id);
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('branches.trash.view');
    }

    public function restore(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.restore');
    }

    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasPermission('branches.force_delete');
    }
}
