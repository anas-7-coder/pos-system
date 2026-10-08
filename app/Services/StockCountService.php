<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
// use App\Models\StockBalance;
use App\Models\StockCount;
use App\Models\StockCountItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockCountService
{
    public function __construct(private InventoryService $inventoryService) {}

    public function create(User $user, array $data): StockCount
    {
        $warehouse = $this->validateWarehouse($user, (int) $data['warehouse_id']);

        return DB::transaction(function () use ($warehouse, $data) {
            $stockCount = StockCount::create([
                'warehouse_id' => $warehouse->id,
                'count_number' => $data['count_number'],
                'count_date' => $data['count_date'],
                'status' => 'draft',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $this->createCountItem($stockCount, $item);
            }

            return $stockCount->fresh(['warehouse', 'items.product', 'items.productVariant']);
        });
    }

    public function update(User $user, StockCount $stockCount, array $data): StockCount
    {
        if ($stockCount->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft stock counts can be updated.']);
        }

        $warehouse = $this->validateWarehouse($user, (int) $data['warehouse_id']);

        return DB::transaction(function () use ($stockCount, $warehouse, $data) {
            $stockCount->update([
                'warehouse_id' => $warehouse->id,
                'count_number' => $data['count_number'],
                'count_date' => $data['count_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $stockCount->items()->delete();

            foreach ($data['items'] as $item) {
                $this->createCountItem($stockCount, $item);
            }

            return $stockCount->fresh(['warehouse', 'items.product', 'items.productVariant']);
        });
    }

    // public function post(User $user, StockCount $stockCount): void
    // {
    //     DB::transaction(function () use ($user, $stockCount)
    //     {
    //         $count = StockCount::query()
    //             ->with(['warehouse', 'items.product', 'items.productVariant'])
    //             ->whereKey($stockCount->id)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         if ($count->status !== 'draft') {
    //             throw ValidationException::withMessages(['status' => 'Only draft stock counts can be posted.']);
    //         }

    //         $this->validateWarehouse($user, (int) $count->warehouse_id);

    //         if ($count->items->isEmpty()) {
    //             throw ValidationException::withMessages(['items' => 'A stock count must contain at least one item.']);
    //         }

    //         foreach ($count->items as $item) {
    //             $this->inventoryService->reconcile(
    //                 user           : $user,
    //                 warehouseId    : (int) $count->warehouse_id,
    //                 productId      : (int) $item->product_id,
    //                 variantId      : $item->product_variant_id ? (int) $item->product_variant_id : null,
    //                 countedQuantity: (float) $item->counted_quantity,
    //                 reference      : $count,
    //                 notes          : 'Stock count ' . $count->count_number
    //             );

    //             /*
    //              * Store the difference against the actual
    //              * quantity at posting time.
    //              */
    //             $currentQuantity = $this->inventoryService->currentStock( (int) $count->warehouse_id, (int) $item->product_id, $item->product_variant_id ? (int) $item->product_variant_id : null );
    //             $item->update([
    //                 'system_quantity' => $currentQuantity,
    //                 'difference'      => (float) $item->counted_quantity - $currentQuantity,
    //             ]);
    //         }

    //         $count->update([
    //             'status'    => 'posted',
    //             'posted_at' => now(),
    //             'posted_by' => $user->id,
    //         ]);
    //     });
    // }

    public function post(User $user, StockCount $stockCount): void
    {
        DB::transaction(function () use ($user, $stockCount) {

            $count = StockCount::query()
                ->with(['warehouse', 'items.product', 'items.productVariant'])
                ->whereKey($stockCount->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($count->status !== 'draft') {
                throw ValidationException::withMessages(['status' => 'Only draft stock counts can be posted.']);
            }

            $this->validateWarehouse($user, (int) $count->warehouse_id);

            if ($count->items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'A stock count must contain at least one item.']);
            }

            foreach ($count->items as $item) {
                $warehouseId = (int) $count->warehouse_id;
                $productId = (int) $item->product_id;

                $variantId = $item->product_variant_id ? (int) $item->product_variant_id : null;

                $result = $this->inventoryService->reconcileWithSnapshot(
                    user           : $user,
                    warehouseId    : $warehouseId,
                    productId      : $productId,
                    variantId      : $variantId,
                    countedQuantity: (float) $item->counted_quantity,
                    reference      : $count,
                    notes          : 'Stock count '.$count->count_number
                );

                $item->update([
                    'system_quantity' => $result['system_quantity'],
                    'difference' => $result['difference'],
                ]);
            }

            $count->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => $user->id,
            ]);
        });
    }

    public function cancel(StockCount $stockCount): void
    {
        if ($stockCount->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft stock counts can be cancelled.']);
        }

        DB::transaction(function () use ($stockCount) {
            $stockCount->update(['status' => 'cancelled']);
        });
    }

    private function createCountItem(StockCount $stockCount, array $item): void
    {
        $product = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->find($item['product_id']);

        if (! $product) {
            throw ValidationException::withMessages(['items' => 'The selected product is not active or inventory is not tracked.']);
        }

        if (! empty($item['product_variant_id'])) {
            $variant = ProductVariant::query()
                ->where('status', 'Active')
                ->find($item['product_variant_id']);

            if (! $variant) {
                throw ValidationException::withMessages(['items' => 'The selected variant is not active.']);
            }

            if ((int) $variant->product_id !== (int) $product->id) {
                throw ValidationException::withMessages(['items' => 'The selected variant does not belong to the selected product.']);
            }
        }

        $systemQuantity = $this->inventoryService->currentStock((int) $stockCount->warehouse_id, (int) $product->id, ! empty($item['product_variant_id']) ? (int) $item['product_variant_id'] : null);

        StockCountItem::create([
            'stock_count_id' => $stockCount->id,
            'product_id' => $product->id,
            'product_variant_id' => $item['product_variant_id'] ?? null,
            'system_quantity' => $systemQuantity,
            'counted_quantity' => (float) $item['counted_quantity'],
            'difference' => (float) $item['counted_quantity'] - $systemQuantity,
        ]);
    }

    private function validateWarehouse(User $user, int $warehouseId): Warehouse
    {
        $warehouse = Warehouse::query()
            ->where('status', 'Active')
            ->find($warehouseId);

        if (! $warehouse) {
            throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
        }

        if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($warehouse->branch_id)) {
            throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
        }

        return $warehouse;
    }
}
