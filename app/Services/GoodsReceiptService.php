<?php

namespace App\Services;

use App\Models\GoodsReceipt;
// use App\Models\GoodsReceiptItem;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
// use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GoodsReceiptService
{
    public function __construct(private InventoryService $inventoryService) {}

    public function create(User $user, PurchaseOrder $purchaseOrder, array $data): GoodsReceipt
    {
        $this->ensurePurchaseOrderCanBeReceived($purchaseOrder, $user);

        return DB::transaction(function () use ($purchaseOrder, $data) {
            $receipt = GoodsReceipt::create([
                'purchase_order_id' => $purchaseOrder->id,
                'supplier_id' => $purchaseOrder->supplier_id,
                'branch_id' => $purchaseOrder->branch_id,
                'warehouse_id' => $purchaseOrder->warehouse_id,
                'receipt_number' => $data['receipt_number'],
                'receipt_date' => $data['receipt_date'],
                'status' => 'draft',
                'notes' => $data['notes'] ?? null,
            ]);

            $this->replaceItems($receipt, $purchaseOrder, $data['items']);
            $this->calculateTotals($receipt);

            return $receipt->fresh([
                'purchaseOrder',
                'supplier',
                'branch',
                'warehouse',
                'items.product',
                'items.productVariant',
            ]);
        });
    }

    public function update(GoodsReceipt $goodsReceipt, array $data): GoodsReceipt
    {
        if ($goodsReceipt->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft goods receipts can be updated.']);
        }

        return DB::transaction(function () use ($goodsReceipt, $data) {
            $goodsReceipt->update([
                'receipt_number' => $data['receipt_number'],
                'receipt_date' => $data['receipt_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $goodsReceipt->items()->delete();
            $goodsReceipt->load('purchaseOrder');

            $this->replaceItems($goodsReceipt, $goodsReceipt->purchaseOrder, $data['items']);
            $this->calculateTotals($goodsReceipt);

            return $goodsReceipt->fresh([
                'purchaseOrder',
                'supplier',
                'branch',
                'warehouse',
                'items.product',
                'items.productVariant',
            ]);
        });
    }

    public function post(User $user, GoodsReceipt $goodsReceipt): void
    {
        DB::transaction(function () use ($user, $goodsReceipt) {
            $receipt = GoodsReceipt::query()
                ->whereKey($goodsReceipt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($receipt->status !== 'draft') {
                throw ValidationException::withMessages(['status' => 'Only draft goods receipts can be posted.']);
            }

            $purchaseOrder = PurchaseOrder::query()
                ->whereKey($receipt->purchase_order_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($purchaseOrder->status !== 'approved') {
                throw ValidationException::withMessages(['status' => 'The purchase order must be approved before receiving goods.']);
            }

            $receipt->load('items.product');
            $receivedQuantities = $this->postedQuantities($purchaseOrder->id);

            foreach ($receipt->items as $item) {
                $purchaseOrderItem = PurchaseOrderItem::query()
                    ->whereKey($item->purchase_order_item_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $purchaseOrderItem->purchase_order_id !== $purchaseOrder->id
                ) {
                    throw ValidationException::withMessages(['items' => 'A receipt item does not belong to this purchase order.']);
                }

                $alreadyReceived = (float) ($receivedQuantities[$purchaseOrderItem->id] ?? 0);
                $orderedQuantity = (float) $purchaseOrderItem->quantity;

                $remaining = $orderedQuantity - $alreadyReceived;
                $receiving = (float) $item->received_quantity;

                if ($receiving > $remaining) {
                    throw ValidationException::withMessages(['items' => "Cannot receive more than the remaining quantity for {$item->product->name}."]);
                }

                $product = Product::query()
                    ->whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->track_inventory) {
                    // StockMovement::create([
                    //     'warehouse_id'       => $receipt->warehouse_id,
                    //     'product_id'         => $item->product_id,
                    //     'product_variant_id' => $item->product_variant_id,
                    //     'movement_type'      => 'purchase_receipt',
                    //     'quantity'           => $receiving,
                    //     'unit_cost'          => $item->unit_cost,
                    //     'reference_type'     => GoodsReceipt::class,
                    //     'reference_id'       => $receipt->id,
                    //     'created_by'         => $user->id,
                    //     'occurred_at'        => now(),
                    //     'notes'              => 'Goods received from purchase order ' . $purchaseOrder->po_number,
                    // ]);

                    $this->inventoryService->increase(
                        user        : $user,
                        warehouseId : $receipt->warehouse_id,
                        productId   : $item->product_id,
                        variantId   : $item->product_variant_id,
                        quantity    : $receiving,
                        unitCost    : (float) $item->unit_cost,
                        movementType: 'purchase_receipt',
                        reference   : $receipt,
                        notes       : 'Goods received from purchase order '.$purchaseOrder->po_number
                    );
                }
            }

            $receipt->update([
                'status' => 'posted',
                'posted_at' => now(),
                'posted_by' => $user->id,
            ]);

            $this->closePurchaseOrderIfFullyReceived($purchaseOrder);
        });
    }

    public function cancel(GoodsReceipt $goodsReceipt): void
    {
        if ($goodsReceipt->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft goods receipts can be cancelled.']);
        }

        DB::transaction(function () use ($goodsReceipt) {
            $goodsReceipt->update(['status' => 'cancelled']);
        });
    }

    private function ensurePurchaseOrderCanBeReceived(PurchaseOrder $purchaseOrder, User $user): void
    {
        if ($purchaseOrder->status !== 'approved') {
            throw ValidationException::withMessages(['status' => 'Only approved purchase orders can receive goods.']);
        }

        if (! $user->hasBranchAccess($purchaseOrder->branch_id)) {
            throw ValidationException::withMessages(['branch_id' => 'You do not have access to this branch.']);
        }
    }

    private function replaceItems(GoodsReceipt $receipt, PurchaseOrder $purchaseOrder, array $items): void
    {
        foreach ($items as $item) {
            $purchaseOrderItem = PurchaseOrderItem::query()
                ->with('product')
                ->findOrFail($item['purchase_order_item_id']);

            if ($purchaseOrderItem->purchase_order_id !== $purchaseOrder->id) {
                throw ValidationException::withMessages(['items' => 'Selected item does not belong to this purchase order.']);
            }

            $quantity = (float) $purchaseOrderItem->quantity;

            if ($quantity <= 0) {
                throw ValidationException::withMessages(['items' => 'Purchase order contains an invalid quantity.']);
            }

            $receivedQuantity = (float) $item['received_quantity'];

            $ratio = $receivedQuantity / $quantity;

            $discount = (float) $purchaseOrderItem->discount_amount * $ratio;
            $tax = (float) $purchaseOrderItem->tax_amount * $ratio;

            $gross = $receivedQuantity * (float) $purchaseOrderItem->unit_cost;

            $lineTotal = $gross - $discount + $tax;

            $receipt->items()->create([
                'purchase_order_item_id' => $purchaseOrderItem->id,
                'product_id' => $purchaseOrderItem->product_id,
                'product_variant_id' => $purchaseOrderItem->product_variant_id,
                'received_quantity' => $receivedQuantity,
                'unit_cost' => $purchaseOrderItem->unit_cost,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'line_total' => $lineTotal,
            ]);
        }
    }

    private function calculateTotals(GoodsReceipt $goodsReceipt): void
    {
        $goodsReceipt->load('items');

        $subtotal = $goodsReceipt->items->sum(
            fn ($item) => (float) $item->received_quantity * (float) $item->unit_cost
        );

        $discount = $goodsReceipt->items->sum(
            fn ($item) => (float) $item->discount_amount
        );

        $tax = $goodsReceipt->items->sum(
            fn ($item) => (float) $item->tax_amount
        );

        $total = $subtotal - $discount + $tax;

        $goodsReceipt->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'grand_total' => $total,
        ]);
    }

    private function postedQuantities(int $purchaseOrderId)
    {
        return DB::table('goods_receipt_items')
            ->join('goods_receipts', 'goods_receipt_items.goods_receipt_id', '=', 'goods_receipts.id')
            ->where('goods_receipts.purchase_order_id', $purchaseOrderId)
            ->where('goods_receipts.status', 'posted')
            ->select('goods_receipt_items.purchase_order_item_id')
            ->selectRaw('SUM(goods_receipt_items.received_quantity) as received_quantity')
            ->groupBy('goods_receipt_items.purchase_order_item_id')
            ->pluck('received_quantity', 'purchase_order_item_id');
    }

    private function closePurchaseOrderIfFullyReceived(PurchaseOrder $purchaseOrder): void
    {
        $postedQuantities = $this->postedQuantities($purchaseOrder->id);

        $allReceived = true;

        foreach ($purchaseOrder->items as $item) {
            $received = (float) ($postedQuantities[$item->id] ?? 0);
            $ordered = (float) $item->quantity;

            if ($received < $ordered) {
                $allReceived = false;
                break;
            }
        }

        if ($allReceived) {
            $purchaseOrder->update(['status' => 'closed']);
        }
    }
}
