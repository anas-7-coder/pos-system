<?php

namespace App\Policies;

use App\Models\TaxClass;
use App\Models\User;

class TaxClassPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('tax_classes.view');
    }

    public function view(User $user, TaxClass $taxClass): bool
    {
        return $user->hasPermission('tax_classes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('tax_classes.create');
    }

    public function update(User $user, TaxClass $taxClass): bool
    {
        return $user->hasPermission('tax_classes.update');
    }

    public function delete(User $user, TaxClass $taxClass): bool
    {
        return $user->hasPermission('tax_classes.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('tax_classes.trash.view');
    }

    public function restore(User $user, TaxClass $taxClass): bool
    {
        return $user->hasPermission('tax_classes.restore');
    }

    public function forceDelete(User $user, TaxClass $taxClass): bool
    {
        return $user->hasPermission('tax_classes.force_delete');
    }
}
