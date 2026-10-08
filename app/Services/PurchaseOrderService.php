<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseOrderService
{
    public function create(User $user, array $data): PurchaseOrder
    {
        $this->validateBusinessRules($user, $data);

        return DB::transaction(function () use ($data) {

            $items = $data['items'];
            unset($data['items']);
            $data['subtotal'] = 0;
            $data['discount_amount'] = 0;
            $data['tax_amount'] = 0;
            $data['grand_total'] = 0;
            $data['status'] = 'draft';
            $purchaseOrder = PurchaseOrder::create($data);

            $this->createItems($purchaseOrder, $items);
            $this->calculateTotals($purchaseOrder);

            return $purchaseOrder->fresh(['supplier', 'branch', 'warehouse', 'items.product', 'items.productVariant']);
        });
    }

    public function update(User $user, PurchaseOrder $purchaseOrder, array $data): PurchaseOrder
    {
        if ($purchaseOrder->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft purchase orders can be updated.']);
        }

        $this->validateBusinessRules($user, $data);

        return DB::transaction(function () use ($purchaseOrder, $data) {
            $items = $data['items'];
            unset($data['items']);
            $data['subtotal'] = 0;
            $data['discount_amount'] = 0;
            $data['tax_amount'] = 0;
            $data['grand_total'] = 0;
            $purchaseOrder->update($data);
            $purchaseOrder->items()->delete();

            $this->createItems($purchaseOrder, $items);
            $this->calculateTotals($purchaseOrder);

            return $purchaseOrder->fresh(['supplier', 'branch', 'warehouse', 'items.product', 'items.productVariant']);
        });
    }

    public function cancel(PurchaseOrder $purchaseOrder): void
    {
        if (in_array($purchaseOrder->status, ['closed', 'cancelled'], true)) {
            throw ValidationException::withMessages(['status' => 'This purchase order cannot be cancelled.']);
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->update(['status' => 'cancelled']);
        });
    }

    private function validateBusinessRules(User $user, array $data): void
    {
        $supplier = Supplier::query()
            ->where('status', 'Active')
            ->find($data['supplier_id']);

        if (! $supplier) {
            throw ValidationException::withMessages(['supplier_id' => 'The selected supplier is not active.']);
        }

        $branch = Branch::query()
            ->where('status', 'Active')
            ->find($data['branch_id']);

        if (! $branch) {
            throw ValidationException::withMessages(['branch_id' => 'The selected branch is not active.']);
        }

        if (! $user->hasBranchAccess($branch->id)) {
            throw ValidationException::withMessages(['branch_id' => 'You do not have access to this branch.']);
        }

        $warehouse = Warehouse::query()
            ->where('status', 'Active')
            ->find($data['warehouse_id']);

        if (! $warehouse) {
            throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
        }

        if ($warehouse->branch_id !== $branch->id) {
            throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse does not belong to the selected branch.']);
        }

        foreach ($data['items'] as $index => $item) {
            $product = Product::query()
                ->where('status', 'Active')
                ->find($item['product_id']);

            if (! $product) {
                throw ValidationException::withMessages(["items.$index.product_id" => 'The selected product is not active.']);
            }

            if (! empty($item['product_variant_id'])) {
                $variant = ProductVariant::query()
                    ->where('status', 'Active')
                    ->find($item['product_variant_id']);

                if (! $variant) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant is not active.']);
                }

                if ($variant->product_id !== $product->id) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant does not belong to the selected product.']);
                }
            }

            $quantity = (float) $item['quantity'];
            $unitCost = (float) $item['unit_cost'];
            $discount = (float) ($item['discount_amount'] ?? 0);
            $tax = (float) ($item['tax_amount'] ?? 0);
            $gross = $quantity * $unitCost;

            if ($discount > $gross) {
                throw ValidationException::withMessages(["items.$index.discount_amount" => 'Discount cannot be greater than the line amount.']);
            }

            if (($gross - $discount + $tax) < 0) {
                throw ValidationException::withMessages(["items.$index.tax_amount" => 'Invalid tax amount.']);
            }
        }
    }

    private function createItems(PurchaseOrder $purchaseOrder, array $items): void
    {
        foreach ($items as $item) {
            $quantity = (float) $item['quantity'];
            $unitCost = (float) $item['unit_cost'];
            $discount = (float) ($item['discount_amount'] ?? 0);
            $tax = (float) ($item['tax_amount'] ?? 0);
            $gross = $quantity * $unitCost;
            $lineTotal = $gross - $discount + $tax;

            $purchaseOrder->items()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'line_total' => $lineTotal,
            ]);
        }
    }

    private function calculateTotals(PurchaseOrder $purchaseOrder): void
    {
        $purchaseOrder->load('items');

        $subtotal = $purchaseOrder->items->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_cost);
        $discount = $purchaseOrder->items->sum(fn ($item) => (float) $item->discount_amount);

        $tax = $purchaseOrder->items->sum(fn ($item) => (float) $item->tax_amount);

        $grandTotal = $subtotal - $discount + $tax;

        $purchaseOrder->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal,
        ]);
    }

    public function submit(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft purchase orders can be submitted.']);
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->update(['status' => 'submitted']);
        });
    }

    public function approve(PurchaseOrder $purchaseOrder): void
    {
        if ($purchaseOrder->status !== 'submitted') {
            throw ValidationException::withMessages(['status' => 'Only submitted purchase orders can be approved.']);
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->update(['status' => 'approved']);
        });
    }
}
