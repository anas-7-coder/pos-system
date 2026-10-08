<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('inventory.view');
    }

    public function view(User $user, StockMovement $stockMovement): bool
    {
        return $user->hasPermission('inventory.view') && ($user->hasRole('super_admin') || $user->hasBranchAccess($stockMovement->warehouse->branch_id));
    }

    public function adjust(User $user): bool
    {
        return $user->hasPermission('inventory.adjust');
    }
}
