<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function increase(User $user, int $warehouseId, int $productId, ?int $variantId, float $quantity, ?float $unitCost, string $movementType, ?Model $reference = null, ?string $notes = null): StockMovement
    {
        return $this->mutateStock(
            user          : $user,
            warehouseId   : $warehouseId,
            productId     : $productId,
            variantId     : $variantId,
            signedQuantity: $quantity,
            unitCost      : $unitCost,
            movementType  : $movementType,
            reference     : $reference,
            notes         : $notes
        );
    }

    public function decrease(User $user, int $warehouseId, int $productId, ?int $variantId, float $quantity, ?float $unitCost, string $movementType, ?Model $reference = null, ?string $notes = null): StockMovement
    {
        return $this->mutateStock(
            user          : $user,
            warehouseId   : $warehouseId,
            productId     : $productId,
            variantId     : $variantId,
            signedQuantity: -$quantity,
            unitCost      : $unitCost,
            movementType  : $movementType,
            reference     : $reference,
            notes         : $notes
        );
    }

    public function adjust(User $user, int $warehouseId, int $productId, ?int $variantId, float $quantity, string $direction, string $reason): StockMovement
    {
        $signedQuantity = $direction === 'in' ? $quantity : -$quantity;

        return $this->mutateStock(
            user          : $user,
            warehouseId   : $warehouseId,
            productId     : $productId,
            variantId     : $variantId,
            signedQuantity: $signedQuantity,
            unitCost      : null,
            movementType  : $direction === 'in' ? 'adjustment_in' : 'adjustment_out',
            reference     : null,
            notes         : $reason
        );
    }

    public function currentStock(int $warehouseId, int $productId, ?int $variantId = null): float
    {
        $key = $this->balanceKey($warehouseId, $productId, $variantId);

        return (float) (StockBalance::query()->where('balance_key', $key)->value('quantity') ?? 0);
    }

    private function mutateStock(
        User $user, int $warehouseId, int $productId, ?int $variantId, float $signedQuantity, ?float $unitCost, string $movementType, ?Model $reference, ?string $notes): StockMovement
    {
        if ($signedQuantity == 0) {
            throw ValidationException::withMessages(['quantity' => 'Stock quantity must be greater than zero.']);
        }

        return DB::transaction(function () use (
            $user, $warehouseId, $productId, $variantId, $signedQuantity, $unitCost, $movementType, $reference, $notes) {
            $warehouse = Warehouse::query()->with('branch')->where('status', 'Active')->find($warehouseId);

            if (! $warehouse) {
                throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
            }

            if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($warehouse->branch_id)) {
                throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
            }

            $product = Product::query()->where('status', 'Active')->find($productId);

            if (! $product) {
                throw ValidationException::withMessages(['product_id' => 'The selected product is not active.']);
            }

            if (! $product->track_inventory) {
                throw ValidationException::withMessages(['product_id' => 'Inventory is not tracked for this product.']);
            }

            if ($variantId) {
                $variant = ProductVariant::query()->where('status', 'Active')->find($variantId);

                if (! $variant) {
                    throw ValidationException::withMessages(['product_variant_id' => 'The selected variant is not active.']);
                }

                if ((int) $variant->product_id !== (int) $product->id) {
                    throw ValidationException::withMessages(['product_variant_id' => 'The selected variant does not belong to this product.']);
                }
            }

            $balanceKey = $this->balanceKey($warehouseId, $productId, $variantId);

            /*
             * Create the balance row if it doesn't exist.
             */
            StockBalance::query()->insertOrIgnore([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'balance_key' => $balanceKey,
                'quantity' => 0,
                'reserved_quantity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
             * Lock the balance row before changing it.
             */
            $balance = StockBalance::query()->where('balance_key', $balanceKey)->lockForUpdate()->firstOrFail();

            $currentQuantity = (float) $balance->quantity;

            $newQuantity = $currentQuantity + $signedQuantity;

            if ($signedQuantity < 0 && ! $product->allow_negative_stock && $newQuantity < 0) {
                throw ValidationException::withMessages(['quantity' => "Insufficient stock for {$product->name}."."Available: {$currentQuantity}."]);
            }

            $balance->update(['quantity' => $newQuantity]);

            return StockMovement::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'movement_type' => $movementType,
                'quantity' => $signedQuantity,
                'unit_cost' => $unitCost,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
                'created_by' => $user->id,
                'occurred_at' => now(),
                'notes' => $notes,
            ]);
        });
    }

    private function balanceKey(int $warehouseId, int $productId, ?int $variantId): string
    {
        return $warehouseId.':'.$productId.':'.($variantId ?? 0);
    }

    public function setReorderLevel(User $user, int $warehouseId, int $productId, ?int $variantId, float $reorderLevel): StockBalance
    {
        if ($reorderLevel < 0) {
            throw ValidationException::withMessages(['reorder_level' => 'Reorder level cannot be negative.']);
        }

        return DB::transaction(function () use ($user, $warehouseId, $productId, $variantId, $reorderLevel
        ) {
            $warehouse = Warehouse::query()
                ->where('status', 'Active')
                ->find($warehouseId);

            if (! $warehouse) {
                throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
            }

            if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($warehouse->branch_id)) {
                throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
            }

            $product = Product::query()
                ->where('status', 'Active')
                ->find($productId);

            if (! $product) {
                throw ValidationException::withMessages(['product_id' => 'The selected product is not active.']);
            }

            $balanceKey = $this->balanceKey($warehouseId, $productId, $variantId);

            StockBalance::query()->insertOrIgnore([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'balance_key' => $balanceKey,
                'quantity' => 0,
                'reorder_level' => 0,
                'reserved_quantity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $balance = StockBalance::query()
                ->where('balance_key', $balanceKey)
                ->lockForUpdate()
                ->firstOrFail();

            $balance->update(['reorder_level' => $reorderLevel]);

            return $balance->fresh(['warehouse', 'product', 'productVariant']);
        });
    }

    // public function reconcile(User $user, int $warehouseId, int $productId, ?int $variantId, float $countedQuantity, Model $reference, string $notes): ?StockMovement
    // {
    //     if ($countedQuantity < 0)
    //     {
    //         throw ValidationException::withMessages(['counted_quantity' => 'Counted quantity cannot be negative.']);
    //     }

    //     return DB::transaction(function () use ($user, $warehouseId, $productId, $variantId, $countedQuantity, $reference, $notes)
    //     {
    //         $warehouse = Warehouse::query()
    //             ->where('status', 'Active')
    //             ->find($warehouseId);

    //         if (!$warehouse)
    //         {
    //             throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
    //         }

    //         if (!$user->hasRole('super_admin') && !$user->hasBranchAccess($warehouse->branch_id))
    //         {
    //             throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
    //         }

    //         $product = Product::query()
    //             ->where('status', 'Active')
    //             ->find($productId);

    //         if (!$product)
    //         {
    //             throw ValidationException::withMessages(['product_id' => 'The selected product is not active.']);
    //         }

    //         if (!$product->track_inventory)
    //         {
    //             throw ValidationException::withMessages(['product_id' => 'Inventory is not tracked for this product.']);
    //         }

    //         if ($variantId)
    //         {
    //             $variant = ProductVariant::query()
    //                 ->where('status', 'Active')
    //                 ->find($variantId);

    //             if (!$variant)
    //             {
    //                 throw ValidationException::withMessages(['product_variant_id' => 'The selected variant is not active.']);
    //             }

    //             if ( (int) $variant->product_id !== (int) $product->id)
    //             {
    //                 throw ValidationException::withMessages(['product_variant_id' => 'The selected variant does not belong to this product.']);
    //             }
    //         }

    //         $balanceKey = $this->balanceKey($warehouseId, $productId, $variantId);

    //         StockBalance::query()->insertOrIgnore([
    //             'warehouse_id'       => $warehouseId,
    //             'product_id'         => $productId,
    //             'product_variant_id' => $variantId,
    //             'balance_key'        => $balanceKey,
    //             'quantity'           => 0,
    //             'reorder_level'      => 0,
    //             'reserved_quantity'  => 0,
    //             'created_at'         => now(),
    //             'updated_at'         => now(),
    //         ]);

    //         $balance = StockBalance::query()
    //             ->where('balance_key', $balanceKey)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         $currentQuantity = (float) $balance->quantity;
    //         $difference      = $countedQuantity - $currentQuantity;

    //         /*
    //         * No movement is necessary if the physical count
    //         * exactly matches the current system quantity.
    //         */
    //         if ($difference == 0)
    //         {
    //             return null;
    //         }

    //         if ($difference < 0 && !$product->allow_negative_stock && $countedQuantity < 0)
    //         {
    //             throw ValidationException::withMessages(['counted_quantity' => 'Invalid stock count.']);
    //         }

    //         $balance->update(['quantity' => $countedQuantity]);

    //         return StockMovement::create([
    //             'warehouse_id'       => $warehouseId,
    //             'product_id'         => $productId,
    //             'product_variant_id' => $variantId,
    //             'movement_type'      => 'stock_count_adjustment',
    //             'quantity'           => $difference,
    //             'unit_cost'          => null,
    //             'reference_type'     => $reference->getMorphClass(),
    //             'reference_id'       => $reference->getKey(),
    //             'created_by'         => $user->id,
    //             'occurred_at'        => now(),
    //             'notes'              => $notes,
    //         ]);
    //     });
    // }

    public function reconcile(User $user, int $warehouseId, int $productId, ?int $variantId, float $countedQuantity, Model $reference, string $notes): ?StockMovement
    {
        return $this->reconcileWithSnapshot(
            user           : $user,
            warehouseId    : $warehouseId,
            productId      : $productId,
            variantId      : $variantId,
            countedQuantity: $countedQuantity,
            reference      : $reference,
            notes          : $notes
        )['movement'];
    }

    public function decreaseForSale(User $user, Sale $sale, SaleItem $saleItem): void
    {
        $this->decrease(
            user        : $user,
            warehouseId : $sale->warehouse_id,
            productId   : $saleItem->product_id,
            variantId   : $saleItem->product_variant_id,
            quantity    : $saleItem->quantity,
            unitCost    : $saleItem->product->cost_price ?? null,
            movementType: 'sale',
            reference   : $sale,
            notes       : 'Sale '.$sale->invoice_number
        );
    }

    public function reconcileWithSnapshot(User $user, int $warehouseId, int $productId, ?int $variantId, float $countedQuantity, Model $reference, string $notes): array
    {
        if ($countedQuantity < 0) {
            throw ValidationException::withMessages(['counted_quantity' => 'Counted quantity cannot be negative.']);
        }

        return DB::transaction(function () use ($user, $warehouseId, $productId, $variantId, $countedQuantity, $reference, $notes) {
            $warehouse = Warehouse::query()
                ->where('status', 'Active')
                ->find($warehouseId);

            if (! $warehouse) {
                throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
            }

            if (
                ! $user->hasRole('super_admin') && ! $user->hasBranchAccess($warehouse->branch_id)
            ) {
                throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
            }

            $product = Product::query()
                ->where('status', 'Active')
                ->find($productId);

            if (! $product) {
                throw ValidationException::withMessages(['product_id' => 'The selected product is not active.']);
            }

            if (! $product->track_inventory) {
                throw ValidationException::withMessages(['product_id' => 'Inventory is not tracked for this product.']);
            }

            if ($variantId) {
                $variant = ProductVariant::query()
                    ->where('status', 'Active')
                    ->find($variantId);

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'product_variant_id' => 'The selected variant is not active.',
                    ]);
                }

                if ((int) $variant->product_id !== (int) $product->id) {
                    throw ValidationException::withMessages([
                        'product_variant_id' => 'The selected variant does not belong to this product.']);
                }
            }

            $balanceKey = $this->balanceKey($warehouseId, $productId, $variantId);

            StockBalance::query()->insertOrIgnore([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'balance_key' => $balanceKey,
                'quantity' => 0,
                'reorder_level' => 0,
                'reserved_quantity' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            * Critical:
            * Lock the balance BEFORE taking the system quantity snapshot.
            */
            $balance = StockBalance::query()
                ->where('balance_key', $balanceKey)
                ->lockForUpdate()
                ->firstOrFail();

            $systemQuantity = (float) $balance->quantity;
            $reservedQuantity = (float) $balance->reserved_quantity;

            /*
            * Do not allow a physical count below already-reserved stock.
            */
            if ($countedQuantity < $reservedQuantity) {
                throw ValidationException::withMessages(['counted_quantity' => 'Counted quantity cannot be less than reserved quantity. '."Reserved: {$reservedQuantity}."]);
            }

            $difference = round($countedQuantity - $systemQuantity, 4);

            if (abs($difference) < 0.00005) {
                return [
                    'system_quantity' => $systemQuantity,
                    'difference' => 0.0,
                    'movement' => null,
                ];
            }

            /*
            * The counted quantity is the new authoritative stock quantity.
            */
            $balance->update(['quantity' => $countedQuantity]);

            $movement = StockMovement::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'movement_type' => 'stock_count_adjustment',
                'quantity' => $difference,
                'unit_cost' => null,
                'reference_type' => $reference->getMorphClass(),
                'reference_id' => $reference->getKey(),
                'created_by' => $user->id,
                'occurred_at' => now(),
                'notes' => $notes,
            ]);

            return [
                'system_quantity' => $systemQuantity,
                'difference' => $difference,
                'movement' => $movement,
            ];
        });
    }
}
