<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\RegisterSession;
use App\Models\Sale;
// use App\Models\SaleItem;
use App\Models\TaxRate;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function createDraft(User $user, array $data): Sale
    {
        return DB::transaction(function () use ($user, $data) {
            $session = $this->validateRegisterSession($user, (int) $data['register_session_id']);
            $warehouse = $this->validateWarehouse($user, (int) $data['warehouse_id'], (int) $session->branch_id);
            $customer = null;

            if (! empty($data['customer_id'])) {
                $customer = Customer::query()
                    ->where('status', 'Active')
                    ->find($data['customer_id']);

                if (! $customer) {
                    throw ValidationException::withMessages(['customer_id' => 'The selected customer is not active.']);
                }
            }

            $sale = Sale::create([
                'customer_id' => $customer?->id,
                'branch_id' => $session->branch_id,
                'warehouse_id' => $warehouse->id,
                'register_id' => $session->register_id,
                'register_session_id' => $session->id,
                'cashier_id' => $user->id,
                'invoice_number' => $data['invoice_number'],
                'sale_date' => $data['sale_date'],
                'status' => 'draft',
                'notes' => $data['notes'] ?? null,
            ]);

            $this->replaceItems($sale, $data['items']);
            $this->calculateTotals($sale);

            return $sale->fresh(['customer', 'branch', 'warehouse', 'register', 'registerSession', 'cashier', 'items.product', 'items.productVariant']);
        });
    }

    public function updateDraft(User $user, Sale $sale, array $data): Sale
    {
        if ($sale->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft sales can be updated.']);
        }

        return DB::transaction(function () use ($user, $sale, $data) {
            $session = $this->validateRegisterSession($user, (int) $data['register_session_id']);

            if ((int) $sale->branch_id !== (int) $session->branch_id) {
                throw ValidationException::withMessages(['register_session_id' => 'The register session does not belong to this sale branch.']);
            }

            $warehouse = $this->validateWarehouse($user, (int) $data['warehouse_id'], (int) $session->branch_id);
            $customer = null;

            if (! empty($data['customer_id'])) {
                $customer = Customer::query()
                    ->where('status', 'Active')
                    ->find($data['customer_id']);

                if (! $customer) {
                    throw ValidationException::withMessages(['customer_id' => 'The selected customer is not active.']);
                }
            }

            $sale->update([
                // 'customer_id'         => $data['customer_id'] ?? null,
                'customer_id' => $customer?->id,
                'warehouse_id' => $warehouse->id,
                'register_id' => $session->register_id,
                'register_session_id' => $session->id,
                'invoice_number' => $data['invoice_number'],
                'sale_date' => $data['sale_date'],
                'notes' => $data['notes'] ?? null,
            ]);

            $sale->items()->delete();
            $this->replaceItems($sale, $data['items']);
            $this->calculateTotals($sale);

            return $sale->fresh(['customer', 'branch', 'warehouse', 'register', 'registerSession', 'cashier', 'items.product', 'items.productVariant']);
        });
    }

    public function cancel(Sale $sale): void
    {
        if ($sale->status !== 'draft') {
            throw ValidationException::withMessages(['status' => 'Only draft sales can be cancelled.']);
        }

        DB::transaction(function () use ($sale) {
            $sale->update(['status' => 'cancelled']);
        });
    }

    private function validateRegisterSession(User $user, int $registerSessionId): RegisterSession
    {
        $session = RegisterSession::query()
            ->with('register')
            ->whereKey($registerSessionId)
            ->where('status', 'Open')
            ->first();

        if (! $session) {
            throw ValidationException::withMessages(['register_session_id' => 'The selected register session is not open.']);
        }

        if (! $user->hasRole('super_admin') && (int) $session->user_id !== (int) $user->id) {
            throw ValidationException::withMessages(['register_session_id' => 'This register session does not belong to you.']);
        }

        if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($session->branch_id)) {
            throw ValidationException::withMessages(['register_session_id' => 'You do not have access to this branch.']);
        }

        return $session;
    }

    private function validateWarehouse(User $user, int $warehouseId, int $branchId): Warehouse
    {
        $warehouse = Warehouse::query()
            ->where('status', 'Active')
            ->find($warehouseId);

        if (! $warehouse) {
            throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse is not active.']);
        }

        if ((int) $warehouse->branch_id !== $branchId) {
            throw ValidationException::withMessages(['warehouse_id' => 'The selected warehouse does not belong to the selected branch.']);
        }

        if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($warehouse->branch_id)) {
            throw ValidationException::withMessages(['warehouse_id' => 'You do not have access to this warehouse.']);
        }

        return $warehouse;
    }

    private function replaceItems(Sale $sale, array $items): void
    {
        foreach ($items as $index => $item) {
            $product = Product::query()
                ->with('taxClass')
                ->where('status', 'Active')
                ->find($item['product_id']);

            if (! $product) {
                throw ValidationException::withMessages(["items.$index.product_id" => 'The selected product is not active.']);
            }

            // if (!$product->track_inventory) {
            //     throw ValidationException::withMessages(["items.$index.product_id" => 'This product is not configured for inventory tracking.']);
            // }

            $variant = null;

            if (! empty($item['product_variant_id'])) {
                $variant = ProductVariant::query()
                    ->where('status', 'Active')
                    ->find($item['product_variant_id']);

                if (! $variant) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant is not active.']);
                }

                if ((int) $variant->product_id !== (int) $product->id) {
                    throw ValidationException::withMessages(["items.$index.product_variant_id" => 'The selected variant does not belong to this product.']);
                }
            }

            $quantity = (float) $item['quantity'];
            $discount = (float) ($item['discount_amount'] ?? 0);
            $unitPrice = $this->resolvePrice($product, $variant, $sale->branch_id, $sale->sale_date);
            $gross = $quantity * $unitPrice;

            if ($discount > $gross) {
                throw ValidationException::withMessages(["items.$index.discount_amount" => 'Discount cannot exceed the line amount.']);
            }

            $taxRate = $this->resolveTaxRate($product, $sale->sale_date);
            $taxable = $gross - $discount;
            $taxAmount = round($taxable * $taxRate / 100, 2);
            $lineTotal = $taxable + $taxAmount;

            $sale->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => $discount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,
            ]);
        }
    }

    private function calculateTotals(Sale $sale): void
    {
        $sale->load('items');

        $subtotal = $sale->items->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_price);
        $discount = $sale->items->sum(fn ($item) => (float) $item->discount_amount);
        $tax = $sale->items->sum(fn ($item) => (float) $item->tax_amount);
        $grandTotal = $subtotal - $discount + $tax;

        $sale->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal,
        ]);
    }

    private function resolvePrice(Product $product, ?ProductVariant $variant, int $branchId, $saleDate): float
    {
        $query = ProductPrice::query()
            ->where('product_id', $product->id)
            ->where('price_type', 'retail')
            ->where('status', 'Active')
            ->whereDate('effective_from', '<=', $saleDate)
            ->where(function ($query) use ($saleDate) {
                $query->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $saleDate);
            })
            ->where(function ($query) use ($variant, $branchId) {
                $query->where('branch_id', $branchId)
                    ->orWhereNull('branch_id');

                if ($variant) {
                    $query->where('product_variant_id', $variant->id);
                } else {
                    $query->whereNull('product_variant_id');
                }
            })
            ->orderByRaw('CASE WHEN branch_id = ? THEN 0 ELSE 1 END', [$branchId])
            ->latest('effective_from');

        $price = $query->first();

        if ($price) {
            return (float) $price->price;
        }

        if ($variant?->selling_price !== null) {
            return (float) $variant->selling_price;
        }

        return (float) $product->selling_price;
    }

    private function resolveTaxRate(Product $product, $saleDate): float
    {
        if (! $product->tax_class_id) {
            return 0;
        }

        $rate = TaxRate::query()
            ->where('tax_class_id', $product->tax_class_id)
            ->where('status', 'Active')
            ->whereDate('effective_from', '<=', $saleDate)
            ->where(function ($query) use ($saleDate) {
                $query->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $saleDate);
            })
            ->latest('effective_from')
            ->first();

        return $rate ? (float) $rate->rate : 0;
    }
}
