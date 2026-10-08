<?php

namespace App\Policies;

use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\User;

class GoodsReceiptPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('goods_receipts.view');
    }

    public function view(User $user, GoodsReceipt $goodsReceipt): bool
    {
        return $user->hasPermission('goods_receipts.view') && $user->hasBranchAccess($goodsReceipt->branch_id);
    }

    public function create(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->hasPermission('goods_receipts.create') && $user->hasBranchAccess($purchaseOrder->branch_id);
    }

    public function update(User $user, GoodsReceipt $goodsReceipt): bool
    {
        return $goodsReceipt->status === 'draft' && $user->hasPermission('goods_receipts.update') && $user->hasBranchAccess($goodsReceipt->branch_id);
    }

    public function post(User $user, GoodsReceipt $goodsReceipt): bool
    {
        return $goodsReceipt->status === 'draft' && $user->hasPermission('goods_receipts.post') && $user->hasBranchAccess($goodsReceipt->branch_id);
    }

    public function cancel(User $user, GoodsReceipt $goodsReceipt): bool
    {
        return $goodsReceipt->status === 'draft' && $user->hasPermission('goods_receipts.cancel') && $user->hasBranchAccess($goodsReceipt->branch_id);
    }
}
