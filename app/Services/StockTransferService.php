<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockTransfer;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockTransferService
{
    public function __construct(private InventoryService $inventoryService) {}

    public function create(User $user, array $data): StockTransfer
    {
        $this->validateBusinessRules($user, $data);

        return DB::transaction(function () use ($data) {
            $items = $data['items'];
            unset($data['items']);
            $data['status'] = 'draft';

            $transfer = StockTransfer::create($data);

            foreach ($items as $item) {
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                ]);
            }

            return $transfer->fresh(['fromWarehouse', 'toWarehouse', 'items.product', 'items.productVariant']);
        });
    }

    public function update(User $user, StockTransfer $stockTransfer, array $data): StockTransfer
    {
        if ($stockTransfer->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft stock transfers can be updated.']);
        }

        $this->validateBusinessRules($user, $data);

        return DB::transaction(function () use ($stockTransfer, $data) {
            $items = $data['items'];
            unset($data['items']);
            $data['status'] = 'draft';
            $stockTransfer->update($data);
            $stockTransfer->items()->delete();

            foreach ($items as $item) {
                $stockTransfer->items()->create([
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                ]);
            }

            return $stockTransfer->fresh(['fromWarehouse', 'toWarehouse', 'items.product', 'items.productVariant']);
        });
    }

    // public function post(User $user, StockTransfer $stockTransfer): void
    // {
    //     DB::transaction(function () use ($user, $stockTransfer)
    //     {
    //         $transfer = StockTransfer::query()
    //             ->with('items.product')
    //             ->whereKey($stockTransfer->id)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //         if ($transfer->status !== 'draft')
    //         {
    //             throw ValidationException::withMessages(['status' => 'Only draft stock transfers can be posted.']);
    //         }

    //         $this->validateTransferWarehouses($user, $transfer->from_warehouse_id, $transfer->to_warehouse_id);

    //         if ($transfer->items->isEmpty())
    //         {
    //             throw ValidationException::withMessages(['items' => 'A stock transfer must contain at least one item.']);
    //         }

    //         foreach ($transfer->items as $item) {
    //             $quantity = (float) $item->quantity;

    //             /*
    //              * Check source stock before making any movement.
    //              */
    //             $availableStock = $this->inventoryService->currentStock($transfer->from_warehouse_id, $item->product_id, $item->product_variant_id);

    //             if (!$item->product->allow_negative_stock && $quantity > $availableStock)
    //             {
    //                 throw ValidationException::withMessages(['items' => "Insufficient stock for {$item->product->name}. " . "Available: {$availableStock}."]);
    //             }

    //             /*
    //              * Stock OUT from source warehouse.
    //              */
    //             $this->inventoryService->decrease(
    //                 user        : $user,
    //                 warehouseId : $transfer->from_warehouse_id,
    //                 productId   : $item->product_id,
    //                 variantId   : $item->product_variant_id,
    //                 quantity    : $quantity,
    //                 unitCost    : null,
    //                 movementType: 'transfer_out',
    //                 reference   : $transfer,
    //                 notes       : 'Stock transferred to warehouse ' . $transfer->to_warehouse_id
    //             );

    //             /*
    //              * Stock IN to destination warehouse.
    //              */
    //             $this->inventoryService->increase(
    //                 user        : $user,
    //                 warehouseId : $transfer->to_warehouse_id,
    //                 productId   : $item->product_id,
    //                 variantId   : $item->product_variant_id,
    //                 quantity    : $quantity,
    //                 unitCost    : null,
    //                 movementType: 'transfer_in',
    //                 reference   : $transfer,
    //                 notes       : 'Stock transferred from warehouse ' . $transfer->from_warehouse_id
    //             );
    //         }

    //         $transfer->update([
    //             'status'    => 'posted',
    //             'posted_at' => now(),
    //             'posted_by' => $user->id,
    //         ]);
    //     });
    // }

    public function post(User $user, StockTransfer $stockTransfer): void
    {
        DB::transaction(function () use ($user, $stockTransfer) {

            $transfer = StockTransfer::query()
                ->with(['items.product', 'items.productVariant'])
                ->whereKey($stockTransfer->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transfer->status !== 'draft') {
                throw ValidationException::withMessages(['status' => 'Only draft stock transfers can be posted.']);
            }

            $this->validateTransferWarehouses($user, (int) $transfer->from_warehouse_id, (int) $transfer->to_warehouse_id);

            if ($transfer->items->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'A stock transfer must contain at least one item.']);
            }

            foreach ($transfer->items as $item) {
                $quantity = (float) $item->quantity;

                /*
                * InventoryService::decrease()
                * already locks the stock balance and checks
                * insufficient stock safely.
                */
                $this->inventoryService->decrease(
                    user        : $user,
                    warehouseId : (int) $transfer->from_warehouse_id,
                    productId   : (int) $item->product_id,
                    variantId   : $item->product_variant_id ? (int) $item->product_variant_id : null,
                    quantity    : $quantity,
                    unitCost    : null,
                    movementType: 'transfer_out',
                    reference   : $transfer,
                    notes       : 'Stock transferred to warehouse '.$transfer->to_warehouse_id
                );

                /*
                * Stock IN to destination warehouse.
                */
                $this->inventoryService->increase(
                    user        : $user,
                    warehouseId : (int) $transfer->to_warehouse_id,
                    productId   : (int) $item->product_id,
                    variantId   : $item->product_variant_id ? (int) $item->product_variant_id : null,
                    quantity    : $quantity,
                    unitCost    : null,
                    movementType: 'transfer_in',
                    reference   : $transfer,
                    notes       : 'Stock transferred from warehouse '.$transfer->from_warehouse_id
                );
            }

            $transfer->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => $user->id,
            ]);
        });
    }

    public function cancel(StockTransfer $stockTransfer): void
    {
        if ($stockTransfer->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft stock transfers can be cancelled.']);
        }

        DB::transaction(function () use ($stockTransfer) {
            $stockTransfer->update(['status' => 'cancelled']);
        });
    }

    private function validateBusinessRules(User $user, array $data): void
    {
        $this->validateTransferWarehouses($user, $data['from_warehouse_id'], $data['to_warehouse_id']);
        $items = $data['items'] ?? [];

        if (count($items) === 0) {
            throw ValidationException::withMessages(['items' => 'A stock transfer must contain at least one item.']);
        }

        foreach ($items as $index => $item) {
            $product = Product::query()
                ->where('status', 'Active')
                ->find($item['product_id']);

            if (! $product) {
                throw ValidationException::withMessages(["items.$index.product_id" => 'The selected product is not active.']);
            }

            if (! $product->track_inventory) {
                throw ValidationException::withMessages(["items.$index.product_id" => 'Inventory is not tracked for this product.']);
            }

            if (! empty($item['product_variant_id'])) {
                $variant = ProductVariant::query()
                    ->where('status', 'Active')
                    ->find($item['product_variant_id']);

                if (! $variant) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant is not active.']);
                }

                if ((int) $variant->product_id !== (int) $product->id) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant does not belong to the selected product.']);
                }
            }
        }
    }

    private function validateTransferWarehouses(User $user, int $fromWarehouseId, int $toWarehouseId): void
    {
        if ((int) $fromWarehouseId === (int) $toWarehouseId) {
            throw ValidationException::withMessages(['to_warehouse_id' => 'Source and destination warehouses must be different.']);
        }

        $fromWarehouse = Warehouse::query()
            ->where('status', 'Active')
            ->find($fromWarehouseId);

        if (! $fromWarehouse) {
            throw ValidationException::withMessages(['from_warehouse_id' => 'The source warehouse is not active.']);
        }

        $toWarehouse = Warehouse::query()
            ->where('status', 'Active')
            ->find($toWarehouseId);

        if (! $toWarehouse) {
            throw ValidationException::withMessages(['to_warehouse_id' => 'The destination warehouse is not active.']);
        }

        if ($user->hasRole('super_admin')) {
            return;
        }

        if (! $user->hasBranchAccess($fromWarehouse->branch_id)) {
            throw ValidationException::withMessages(['from_warehouse_id' => 'You do not have access to the source warehouse.']);
        }

        if (! $user->hasBranchAccess($toWarehouse->branch_id)) {
            throw ValidationException::withMessages(['to_warehouse_id' => 'You do not have access to the destination warehouse.']);
        }
    }
}
