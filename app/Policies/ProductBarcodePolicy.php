<?php

namespace App\Policies;

use App\Models\ProductBarcode;
use App\Models\User;

class ProductBarcodePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('product_barcodes.view');
    }

    public function view(User $user, ProductBarcode $barcode): bool
    {
        return $user->hasPermission('product_barcodes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('product_barcodes.create');
    }

    public function update(User $user, ProductBarcode $barcode): bool
    {
        return $user->hasPermission('product_barcodes.update');
    }

    public function delete(User $user, ProductBarcode $barcode): bool
    {
        return $user->hasPermission('product_barcodes.delete');
    }

    public function viewTrash(User $user): bool
    {
        return $user->hasPermission('product_barcodes.trash.view');
    }

    public function restore(User $user, ProductBarcode $barcode): bool
    {
        return $user->hasPermission('product_barcodes.restore');
    }

    public function forceDelete(User $user, ProductBarcode $barcode): bool
    {
        return $user->hasPermission('product_barcodes.force_delete');
    }
}
