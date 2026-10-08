<?php

namespace App\Policies;

use App\Models\StockTransfer;
use App\Models\User;

class StockTransferPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('stock_transfers.view');
    }

    public function view(User $user, StockTransfer $stockTransfer): bool
    {
        if (! $user->hasPermission('stock_transfers.view')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockTransfer->fromWarehouse->branch_id) || $user->hasBranchAccess($stockTransfer->toWarehouse->branch_id);
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('stock_transfers.create');
    }

    public function update(User $user, StockTransfer $stockTransfer): bool
    {
        if ($stockTransfer->status !== 'draft' || ! $user->hasPermission('stock_transfers.update')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockTransfer->fromWarehouse->branch_id) && $user->hasBranchAccess($stockTransfer->toWarehouse->branch_id);
    }

    public function post(User $user, StockTransfer $stockTransfer): bool
    {
        if ($stockTransfer->status !== 'draft' || ! $user->hasPermission('stock_transfers.post')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockTransfer->fromWarehouse->branch_id) && $user->hasBranchAccess($stockTransfer->toWarehouse->branch_id);
    }

    public function cancel(User $user, StockTransfer $stockTransfer): bool
    {
        if ($stockTransfer->status !== 'draft' || ! $user->hasPermission('stock_transfers.cancel')) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasBranchAccess($stockTransfer->fromWarehouse->branch_id) && $user->hasBranchAccess($stockTransfer->toWarehouse->branch_id);
    }
}
