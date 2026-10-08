<?php

namespace App\Policies;

use App\Models\Register;
use App\Models\User;

class RegisterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('registers.view');
    }

    public function view(User $user, Register $register): bool
    {
        return $user->hasPermission('registers.view') && $user->canAccessBranch($register->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('registers.create');
    }

    public function update(User $user, Register $register): bool
    {
        return $user->hasPermission('registers.update') && $user->canAccessBranch($register->branch_id);
    }

    public function delete(User $user, Register $register): bool
    {
        return $user->hasPermission('registers.delete') && $user->canAccessBranch($register->branch_id);
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('registers.trash.view');
    }

    public function restore(User $user, Register $register): bool
    {
        return $user->hasPermission('registers.restore');
    }

    public function forceDelete(User $user, Register $register): bool
    {
        return $user->hasPermission('registers.force_delete');
    }
}
