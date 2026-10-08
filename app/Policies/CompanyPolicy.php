<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('companies.view');
    }

    public function view(User $user, Company $company): bool
    {
        return $user->hasPermission('companies.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('companies.create');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasPermission('companies.update');
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermission('companies.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('companies.trash.view');
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->hasPermission('companies.restore');
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->hasPermission('companies.force_delete');
    }
}
