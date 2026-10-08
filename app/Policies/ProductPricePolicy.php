<?php

namespace App\Policies;

use App\Models\ProductPrice;
use App\Models\User;

class ProductPricePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('product_prices.view');
    }

    public function view(User $user, ProductPrice $productPrice): bool
    {
        return $user->hasPermission('product_prices.view') && $this->canAccessBranch($user, $productPrice);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('product_prices.create');
    }

    public function update(User $user, ProductPrice $productPrice): bool
    {
        return $user->hasPermission('product_prices.update') && $this->canAccessBranch($user, $productPrice);
    }

    public function delete(User $user, ProductPrice $productPrice): bool
    {
        return $user->hasPermission('product_prices.delete') && $this->canAccessBranch($user, $productPrice);
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('product_prices.trash.view');
    }

    public function restore(User $user, ProductPrice $productPrice): bool
    {
        return $user->hasPermission('product_prices.trash.restore') && $this->canAccessBranch($user, $productPrice);
    }

    public function forceDelete(User $user, ProductPrice $productPrice): bool
    {
        return $user->hasPermission('product_prices.trash.force_delete') && $this->canAccessBranch($user, $productPrice);
    }

    private function canAccessBranch(User $user, ProductPrice $productPrice): bool
    {
        if (! $productPrice->branch_id) {
            return true;
        }

        return $user->hasBranchAccess($productPrice->branch_id);
    }
}
