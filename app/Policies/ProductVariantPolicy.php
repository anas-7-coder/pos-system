<?php

namespace App\Policies;

use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('product_variants.view');
    }

    public function view(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermission('product_variants.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('product_variants.create');
    }

    public function update(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermission('product_variants.update');
    }

    public function delete(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermission('product_variants.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('product_variants.trash.view');
    }

    public function restore(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermission('product_variants.restore');
    }

    public function forceDelete(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermission('product_variants.force_delete');
    }
}
