<?php

namespace App\Policies;

use App\Models\TaxRate;
use App\Models\User;

class TaxRatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('tax_rates.view');
    }

    public function view(User $user, TaxRate $taxRate): bool
    {
        return $user->hasPermission('tax_rates.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('tax_rates.create');
    }

    public function update(User $user, TaxRate $taxRate): bool
    {
        return $user->hasPermission('tax_rates.update');
    }

    public function delete(User $user, TaxRate $taxRate): bool
    {
        return $user->hasPermission('tax_rates.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('tax_rates.trash.view');
    }

    public function restore(User $user, TaxRate $taxRate): bool
    {
        return $user->hasPermission('tax_rates.restore');
    }

    public function forceDelete(User $user, TaxRate $taxRate): bool
    {
        return $user->hasPermission('tax_rates.force_delete');
    }
}
