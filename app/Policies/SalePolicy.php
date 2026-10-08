<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('sales.view');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->hasPermission('sales.view') && ($user->hasRole('super_admin') || $user->hasBranchAccess($sale->branch_id));
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('sales.create');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $sale->status === 'draft' && $user->hasPermission('sales.create') && ($user->hasRole('super_admin') || $user->hasBranchAccess($sale->branch_id));
    }

    public function cancel(User $user, Sale $sale): bool
    {
        return $user->hasPermission('sales.cancel') && $sale->status === 'draft' && ($user->hasRole('super_admin') || $user->hasBranchAccess($sale->branch_id));
    }

    public function refund(User $user, Sale $sale): bool
    {
        return $user->hasPermission('sales.refund') && $sale->status === 'completed' && ($user->hasRole('super_admin') || $user->hasBranchAccess($sale->branch_id));
    }

    public function credit(User $user, Sale $sale): bool
    {
        return $user->hasPermission('sales.credit') && $sale->status === 'draft' && $sale->customer_id !== null && $user->canAccessBranch($sale->branch_id);
    }
}
