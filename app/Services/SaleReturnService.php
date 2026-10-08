<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleReturnService
{
    public function __construct(protected InventoryService $inventoryService) {}

    public function createDraft(Sale $sale, array $data, User $user): SaleReturn
    {
        return DB::transaction(function () use ($sale, $data, $user) {

            $sale = Sale::query()
                ->with(['items.product', 'items.productVariant', 'items.returnItems.saleReturn'])
                ->lockForUpdate()
                ->findOrFail($sale->id);

            if (! in_array($sale->status, ['completed', 'partially_refunded'], true)) {
                throw ValidationException::withMessages(['sale' => 'Only completed sales can be returned.']);
            }

            if (! $sale->warehouse_id) {
                throw ValidationException::withMessages(['sale' => 'This sale does not have a warehouse.']);
            }

            $saleItems = $sale->items->keyBy('id');

            /*
             * Combine duplicate sale_item_id entries first.
             * This prevents multiple lines for the same sale item
             * from bypassing the remaining returnable quantity check.
             */
            $requestedQuantities = [];

            foreach ($data['items'] as $inputItem) {
                $saleItemId = (int) $inputItem['sale_item_id'];
                $quantity = (float) $inputItem['quantity'];

                if ($quantity <= 0) {
                    throw ValidationException::withMessages(['items' => 'Return quantity must be greater than zero.']);
                }

                $requestedQuantities[$saleItemId] = ($requestedQuantities[$saleItemId] ?? 0) + $quantity;
            }

            $preparedItems = [];

            foreach ($data['items'] as $inputItem) {
                $saleItemId = (int) $inputItem['sale_item_id'];

                if (! $saleItems->has($saleItemId)) {
                    throw ValidationException::withMessages(['items' => 'Invalid sale item selected.']);
                }

                $saleItem = $saleItems->get($saleItemId);
                $quantity = (float) $inputItem['quantity'];

                $requestedQuantity = (float) $requestedQuantities[$saleItemId];

                $alreadyReturned = $saleItem->returnItems
                    ->filter(fn ($returnItem) => $returnItem->saleReturn && $returnItem->saleReturn->status === 'completed')
                    ->sum('quantity');

                $remainingReturnable = round((float) $saleItem->quantity - (float) $alreadyReturned, 4);

                if ($requestedQuantity > $remainingReturnable) {
                    throw ValidationException::withMessages(['items' => "You cannot return more than the remaining quantity for {$saleItem->product->name}."]);
                }

                $originalQuantity = (float) $saleItem->quantity;

                $ratio = $quantity / $originalQuantity;

                $discountAmount = round((float) $saleItem->discount_amount * $ratio);
                $taxAmount = round((float) $saleItem->tax_amount * $ratio, 2
                );

                $lineTotal = round((float) $saleItem->line_total * $ratio, 2);

                $preparedItems[] = [
                    'sale_item' => $saleItem,
                    'quantity' => $quantity,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineTotal,
                    'reason' => $inputItem['reason'] ?? null,
                ];
            }

            $subtotal = 0;
            $discountAmount = 0;
            $taxAmount = 0;
            $grandTotal = 0;

            foreach ($preparedItems as $item) {
                $saleItem = $item['sale_item'];

                $subtotal += (float) $saleItem->unit_price * $item['quantity'];

                $discountAmount += $item['discount_amount'];
                $taxAmount += $item['tax_amount'];
                $grandTotal += $item['line_total'];
            }

            $return = SaleReturn::create([
                'sale_id' => $sale->id,
                'branch_id' => $sale->branch_id,
                'warehouse_id' => $sale->warehouse_id,
                'created_by' => $user->id,
                'return_number' => 'TEMP-'.uniqid(),
                'return_date' => now(),
                'status' => 'draft',
                'subtotal' => round($subtotal, 2),
                'discount_amount' => round($discountAmount, 2),
                'tax_amount' => round($taxAmount, 2),
                'grand_total' => round($grandTotal, 2),
                'notes' => $data['notes'] ?? null,
            ]);

            $return->update([
                'return_number' => 'RET-'.now()->format('Ymd').'-'.str_pad($return->id, 6, '0', STR_PAD_LEFT), ]);

            foreach ($preparedItems as $item) {
                $saleItem = $item['sale_item'];

                SaleReturnItem::create([
                    'sale_return_id' => $return->id,
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'variant_id' => $saleItem->product_variant_id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $saleItem->unit_price,
                    'discount_amount' => $item['discount_amount'],
                    'tax_rate' => $saleItem->tax_rate,
                    'tax_amount' => $item['tax_amount'],
                    'line_total' => $item['line_total'],
                    'reason' => $item['reason'],
                ]);
            }

            return $return->fresh(['sale', 'items.product', 'items.productVariant']);
        });
    }

    public function complete(SaleReturn $saleReturn, User $user): SaleReturn
    {
        return DB::transaction(function () use ($saleReturn, $user) {

            $saleReturn = SaleReturn::query()
                ->with(['sale', 'items.product', 'items.productVariant'])
                ->lockForUpdate()
                ->findOrFail($saleReturn->id);

            if ($saleReturn->status !== 'draft') {
                throw ValidationException::withMessages(['return' => 'Only draft returns can be completed.']);
            }

            if ($saleReturn->items->isEmpty()) {
                throw ValidationException::withMessages(['return' => 'A return must contain at least one item.']);
            }

            $sale = Sale::query()
                ->with('items')
                ->lockForUpdate()
                ->findOrFail($saleReturn->sale_id);

            /*
             * Group the current return by sale_item_id and validate the
             * combined quantity against the remaining returnable quantity.
             */
            $requestedBySaleItem = $saleReturn->items
                ->groupBy('sale_item_id')
                ->map(fn ($items) => (float) $items->sum('quantity')
                );

            foreach ($requestedBySaleItem as $saleItemId => $requestedQuantity) {
                $saleItem = $sale->items->firstWhere('id', (int) $saleItemId);

                if (! $saleItem) {
                    throw ValidationException::withMessages(['return' => 'Original sale item was not found.']);
                }

                $alreadyReturned = SaleReturnItem::query()
                    ->where('sale_item_id', $saleItem->id)
                    ->whereHas('saleReturn', function ($query) use ($saleReturn) {
                        $query
                            ->where('status', 'completed')
                            ->where('id', '!=', $saleReturn->id);
                    })
                    ->sum('quantity');

                $remainingReturnable = round((float) $saleItem->quantity - (float) $alreadyReturned, 4);

                if ((float) $requestedQuantity > $remainingReturnable) {
                    throw ValidationException::withMessages(['return' => "Return quantity exceeds the remaining quantity for {$saleItem->product->name}."]);
                }
            }

            /*
             * Validate each return line and post inventory.
             */
            foreach ($saleReturn->items as $returnItem) {
                $saleItem = $sale->items->firstWhere('id', $returnItem->sale_item_id);
                if (! $saleItem) {
                    throw ValidationException::withMessages(['return' => 'Original sale item was not found.']);
                }

                if (
                    (int) $returnItem->product_id !== (int) $saleItem->product_id
                    ||
                    (int) $returnItem->variant_id !== (int) $saleItem->product_variant_id
                ) {
                    throw ValidationException::withMessages(['return' => 'The return item no longer matches the original sale item.']);
                }

                if ((float) $returnItem->quantity <= 0) {
                    throw ValidationException::withMessages(['return' => 'Return quantity must be greater than zero.']);
                }

                if ($returnItem->product->track_inventory) {
                    $this->inventoryService->increase(
                        user        : $user,
                        warehouseId : $saleReturn->warehouse_id,
                        productId   : $returnItem->product_id,
                        variantId   : $returnItem->variant_id,
                        quantity    : $returnItem->quantity,
                        unitCost    : $returnItem->product->cost_price,
                        movementType: 'sale_return',
                        reference   : $saleReturn,
                        notes       : 'Returned against '.$sale->invoice_number
                    );
                }
            }

            $saleReturn->update([
                'status' => 'completed',
                'completed_by' => $user->id,
                'completed_at' => now(),
            ]);

            return $saleReturn->fresh(['sale', 'items.product', 'items.productVariant', 'refund']);
        });
    }

    public function cancel(SaleReturn $saleReturn): SaleReturn
    {
        if ($saleReturn->status !== 'draft') {
            throw ValidationException::withMessages(['return' => 'Only draft returns can be cancelled.']);
        }

        $saleReturn->update(['status' => 'cancelled']);

        return $saleReturn->fresh();
    }
}

// namespace App\Services;

// use App\Models\Sale;
// use App\Models\SaleReturn;
// use App\Models\SaleReturnItem;
// use App\Models\User;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Validation\ValidationException;

// class SaleReturnService
// {
//     public function __construct(protected InventoryService $inventoryService) {}

//     public function createDraft(Sale $sale, array $data, User $user): SaleReturn
//     {
//         return DB::transaction(function () use ($sale, $data, $user) {

//             $sale = Sale::query()
//                 ->with(['items.product', 'items.productVariant', 'items.returnItems.saleReturn'])
//                 ->lockForUpdate()
//                 ->findOrFail($sale->id);

//             if (!in_array($sale->status, ['completed', 'partially_refunded'], true))
//             {
//                 throw ValidationException::withMessages(['sale' => 'Only completed sales can be returned.']);
//             }

//             if (!$sale->warehouse_id) {
//                 throw ValidationException::withMessages(['sale' => 'This sale does not have a warehouse.']);
//             }

//             $saleItems           = $sale->items->keyBy('id');
//             $preparedItems       = [];

//             foreach ($data['items'] as $inputItem) {
//                 $saleItemId = (int) $inputItem['sale_item_id'];

//                 if (!$saleItems->has($saleItemId)) {
//                     throw ValidationException::withMessages(['items' => 'Invalid sale item selected.']);
//                 }

//                 $saleItem = $saleItems->get($saleItemId);
//                 $quantity = (float) $inputItem['quantity'];

//                 if ($quantity <= 0) {
//                     throw ValidationException::withMessages(['items' => 'Return quantity must be greater than zero.']);
//                 }

//                 $alreadyReturned = $saleItem->returnItems
//                     ->filter( fn ($returnItem) => $returnItem->saleReturn && $returnItem->saleReturn->status === 'completed')
//                     ->sum('quantity');

//                 $remainingReturnable = (float) $saleItem->quantity - (float) $alreadyReturned;

//                 if ($quantity > $remainingReturnable) {
//                     throw ValidationException::withMessages(['items' => "You cannot return more than the remaining quantity for {$saleItem->product->name}."]);
//                 }

//                 $originalQuantity = (float) $saleItem->quantity;
//                 $ratio            = $quantity / $originalQuantity;
//                 $discountAmount   = round( (float) $saleItem->discount_amount * $ratio, 2 );
//                 $taxAmount        = round( (float) $saleItem->tax_amount * $ratio, 2 );
//                 $lineTotal        = round( (float) $saleItem->line_total * $ratio, 2 );

//                 $preparedItems[] = [
//                     'sale_item'       => $saleItem,
//                     'quantity'        => $quantity,
//                     'discount_amount' => $discountAmount,
//                     'tax_amount'      => $taxAmount,
//                     'line_total'      => $lineTotal,
//                     'reason'          => $inputItem['reason'] ?? null,
//                 ];
//             }

//             $subtotal       = 0;
//             $discountAmount = 0;
//             $taxAmount      = 0;
//             $grandTotal     = 0;

//             foreach ($preparedItems as $item) {
//                 $saleItem        = $item['sale_item'];
//                 $subtotal       += (float) $saleItem->unit_price * $item['quantity'];
//                 $discountAmount += $item['discount_amount'];
//                 $taxAmount      += $item['tax_amount'];
//                 $grandTotal     += $item['line_total'];
//             }

//             $return = SaleReturn::create([
//                 'sale_id'         => $sale->id,
//                 'branch_id'       => $sale->branch_id,
//                 'warehouse_id'    => $sale->warehouse_id,
//                 'created_by'      => $user->id,
//                 'return_number'   => 'TEMP-' . uniqid(),
//                 'return_date'     => now(),
//                 'status'          => 'draft',
//                 'subtotal'        => round($subtotal, 2),
//                 'discount_amount' => round($discountAmount, 2),
//                 'tax_amount'      => round($taxAmount, 2),
//                 'grand_total'     => round($grandTotal, 2),
//                 'notes'           => $data['notes'] ?? null,
//             ]);

//             $return->update(['return_number' => 'RET-' . now()->format('Ymd') . '-' . str_pad($return->id, 6, '0', STR_PAD_LEFT)]);

//             foreach ($preparedItems as $item) {
//                 $saleItem = $item['sale_item'];

//                 SaleReturnItem::create([
//                     'sale_return_id'  => $return->id,
//                     'sale_item_id'    => $saleItem->id,
//                     'product_id'      => $saleItem->product_id,
//                     'variant_id'      => $saleItem->product_variant_id,
//                     'quantity'        => $item['quantity'],
//                     'unit_price'      => $saleItem->unit_price,
//                     'discount_amount' => $item['discount_amount'],
//                     'tax_rate'        => $saleItem->tax_rate,
//                     'tax_amount'      => $item['tax_amount'],
//                     'line_total'      => $item['line_total'],
//                     'reason'          => $item['reason'],
//                 ]);
//             }

//             return $return->fresh(['sale', 'items.product', 'items.productVariant']);
//         });
//     }

//     public function complete(SaleReturn $saleReturn, User $user): SaleReturn
//     {
//         return DB::transaction(function () use ($saleReturn, $user) {

//             $saleReturn = SaleReturn::query()
//                 ->with(['sale', 'items.product', 'items.productVariant'])
//                 ->lockForUpdate()
//                 ->findOrFail($saleReturn->id);

//             if ($saleReturn->status !== 'draft') {
//                 throw ValidationException::withMessages(['return' => 'Only draft returns can be completed.']);
//             }

//             $sale = Sale::query()
//                 ->with('items')
//                 ->lockForUpdate()
//                 ->findOrFail($saleReturn->sale_id);

//             foreach ($saleReturn->items as $returnItem) {
//                 $saleItem = $sale->items->firstWhere('id', $returnItem->sale_item_id);

//                 if (!$saleItem) {
//                     throw ValidationException::withMessages(['return' => 'Original sale item was not found.']);
//                 }

//                 if (
//                     (int) $returnItem->product_id !== (int) $saleItem->product_id ||
//                     (int) $returnItem->variant_id !== (int) $saleItem->product_variant_id
//                 ) {
//                     throw ValidationException::withMessages(['return' => 'The return item no longer matches the original sale item.']);
//                 }

//                 $alreadyReturned = SaleReturnItem::query()
//                     ->where('sale_item_id', $saleItem->id)
//                     ->whereHas('saleReturn', function ($query) use ($saleReturn) {
//                         $query
//                             ->where('status', 'completed')
//                             ->where('id', '!=', $saleReturn->id);
//                     })
//                     ->sum('quantity');

//                 $remainingReturnable = (float) $saleItem->quantity - (float) $alreadyReturned;

//                 if ((float) $returnItem->quantity > $remainingReturnable) {
//                     throw ValidationException::withMessages(['return' => "Return quantity exceeds the remaining quantity for {$returnItem->product->name}."]);
//                 }

//                 if ($returnItem->product->track_inventory) {
//                     $this->inventoryService->increase(
//                         user        : $user,
//                         warehouseId : $saleReturn->warehouse_id,
//                         productId   : $returnItem->product_id,
//                         variantId   : $returnItem->variant_id,
//                         quantity    : $returnItem->quantity,
//                         unitCost    : $returnItem->product->cost_price,
//                         movementType: 'sale_return',
//                         reference   : $saleReturn,
//                         notes       : 'Returned against ' . $sale->invoice_number
//                     );
//                 }
//             }

//             $saleReturn->update([
//                 'status'       => 'completed',
//                 'completed_by' => $user->id,
//                 'completed_at' => now(),
//             ]);

//             return $saleReturn->fresh(['sale', 'items.product', 'items.productVariant', 'refund']);
//         });
//     }

//     public function cancel(SaleReturn $saleReturn): SaleReturn
//     {
//         if ($saleReturn->status !== 'draft') {
//             throw ValidationException::withMessages(['return' => 'Only draft returns can be cancelled.']);
//         }

//         $saleReturn->update(['status' => 'cancelled']);

//         return $saleReturn->fresh();
//     }
// }
