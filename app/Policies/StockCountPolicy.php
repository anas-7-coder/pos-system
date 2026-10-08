<?php

namespace App\Policies;

use App\Models\StockCount;
use App\Models\User;

class StockCountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('stock_counts.view');
    }

    public function view(User $user, StockCount $stockCount): bool
    {
        if (! $user->hasPermission('stock_counts.view')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockCount->warehouse->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('stock_counts.create');
    }

    public function update(User $user, StockCount $stockCount): bool
    {
        if ($stockCount->status !== 'draft' || ! $user->hasPermission('stock_counts.update')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockCount->warehouse->branch_id);
    }

    public function post(User $user, StockCount $stockCount): bool
    {
        if ($stockCount->status !== 'draft' || ! $user->hasPermission('stock_counts.post')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockCount->warehouse->branch_id);
    }

    public function cancel(User $user, StockCount $stockCount): bool
    {
        if ($stockCount->status !== 'draft' || ! $user->hasPermission('stock_counts.cancel')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockCount->warehouse->branch_id);
    }
}
