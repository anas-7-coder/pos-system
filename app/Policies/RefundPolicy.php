<?php

namespace App\Policies;

use App\Models\Refund;
use App\Models\SaleReturn;
use App\Models\User;

class RefundPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('sales.view');
    }

    public function view(User $user, Refund $refund): bool
    {
        return $user->hasPermission('sales.view') && $user->canAccessBranch($refund->saleReturn->branch_id);
    }

    public function create(User $user, SaleReturn $saleReturn): bool
    {
        return $user->hasPermission('sales.refund') && $saleReturn->status === 'completed' && ! $saleReturn->refund && $user->canAccessBranch($saleReturn->branch_id);
    }
}
