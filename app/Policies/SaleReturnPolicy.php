<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\User;

class SaleReturnPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('sales.view');
    }

    public function view(User $user, SaleReturn $saleReturn): bool
    {
        return $user->hasPermission('sales.view') && $user->canAccessBranch($saleReturn->branch_id);
    }

    public function create(User $user, Sale $sale): bool
    {
        return $user->hasPermission('sales.refund') && in_array($sale->status, ['completed', 'partially_refunded'], true) && $user->canAccessBranch($sale->branch_id);
    }

    public function complete(User $user, SaleReturn $saleReturn): bool
    {
        return $user->hasPermission('sales.refund') && $saleReturn->status === 'draft' && $user->canAccessBranch($saleReturn->branch_id);
    }

    public function cancel(User $user, SaleReturn $saleReturn): bool
    {
        return $user->hasPermission('sales.refund') && $saleReturn->status === 'draft' && $user->canAccessBranch($saleReturn->branch_id);
    }
}
