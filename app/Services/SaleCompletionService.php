<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleCompletionService
{
    public function __construct(protected InventoryService $inventoryService, protected CustomerReceivableService $customerReceivableService) {}

    public function complete(Sale $sale): Sale
    {
        return DB::transaction(function () use ($sale) {

            $sale = Sale::query()
                ->with(['items.product', 'items.productVariant', 'payments'])
                ->lockForUpdate()
                ->findOrFail($sale->id);

            if ($sale->status !== 'draft') {
                return $sale;
            }

            $paidAmount = (float) $sale->payments()
                ->where('status', 'posted')
                ->sum('amount');

            $grandTotal = (float) $sale->grand_total;

            if (round($paidAmount, 2) < round($grandTotal, 2)) {
                throw ValidationException::withMessages(['payment' => 'The sale is not fully paid.']);
            }

            foreach ($sale->items as $item) {

                $product = $item->product;

                if (! $product) {
                    throw ValidationException::withMessages(['items' => 'A product in this sale no longer exists.']);
                }

                // if (!$product->track_inventory) {
                //     continue;
                // }

                // $quantity = (float) $item->quantity;

                // if ($quantity <= 0) {
                //     throw ValidationException::withMessages(['items' => 'Sale item quantity must be greater than zero.']);
                // }

                // if (!$product->allow_negative_stock) {

                //     $currentStock = $this->inventoryService->currentStock(
                //         $sale->warehouse_id,
                //         $item->product_id,
                //         $item->product_variant_id
                //     );

                //     if ($currentStock < $quantity) {
                //         throw ValidationException::withMessages(['items' => "Insufficient stock for {$product->name}."]);
                //     }
                // }

                // $this->inventoryService->decreaseForSale(Auth::user(), $sale, $item);

                if ($product->track_inventory) {

                    $quantity = (float) $item->quantity;

                    if ($quantity <= 0) {
                        throw ValidationException::withMessages(['items' => 'Sale item quantity must be greater than zero.']);
                    }

                    // $currentStock = $this->inventoryService->currentStock(
                    //     $sale->warehouse_id,
                    //     $item->product_id,
                    //     $item->product_variant_id
                    // );

                    // if ( !$product->allow_negative_stock && $currentStock < $quantity)
                    // {
                    //     throw ValidationException::withMessages(['items' => "Insufficient stock for {$product->name}. " . "Available: {$currentStock}, Requested: {$quantity}."]);
                    // }

                    $this->inventoryService->decreaseForSale(Auth::user(), $sale, $item);
                }
            }

            $sale->update(['status' => 'completed']);

            return $sale->fresh(['customer', 'branch', 'warehouse', 'register', 'registerSession', 'cashier', 'items.product', 'items.productVariant', 'payments']);
        });
    }

    public function completeOnCredit(Sale $sale, User $user): Sale
    {
        return DB::transaction(function () use ($sale, $user) {
            $sale = Sale::query()
                ->with(['customer', 'items.product', 'items.productVariant', 'payments'])
                ->lockForUpdate()
                ->findOrFail($sale->id);

            if ($sale->status !== 'draft') {
                throw ValidationException::withMessages(['sale' => 'Only draft sales can be completed on credit.']);
            }

            if (! $sale->customer_id || ! $sale->customer) {
                throw ValidationException::withMessages(['customer' => 'A customer is required for a credit sale.']);
            }

            $paidAmount = (float) $sale->payments()
                ->where('status', 'posted')
                ->sum('amount');

            $grandTotal = (float) $sale->grand_total;
            $remainingAmount = round($grandTotal - $paidAmount, 2);

            if ($remainingAmount <= 0) {
                throw ValidationException::withMessages(['sale' => 'This sale is already fully paid.']);
            }

            /*
            * Make sure the remaining amount fits
            * within the customer's credit limit.
            */
            $this->customerReceivableService->validateCreditSale($sale->customer, $remainingAmount);

            /*
            * Decrease inventory exactly like
            * a normal completed sale.
            */
            foreach ($sale->items as $item) {
                $product = $item->product;

                if (! $product) {
                    throw ValidationException::withMessages(['items' => 'A product in this sale no longer exists.']);
                }

                if (! $product->track_inventory) {
                    continue;
                }

                $quantity = (float) $item->quantity;

                // if (!$product->allow_negative_stock) {
                //     $currentStock = $this->inventoryService->currentStock($sale->warehouse_id, $item->product_id, $item->product_variant_id);

                //     if ($currentStock < $quantity) {
                //         throw ValidationException::withMessages(['items' => "Insufficient stock for {$product->name}."]);
                //     }
                // }

                $this->inventoryService->decreaseForSale(user: $user, sale: $sale, saleItem: $item);
            }

            /*
            * Create the receivable for the unpaid part.
            */
            $this->customerReceivableService->recordSaleCredit($sale, $remainingAmount, $user);
            $sale->update(['status' => 'completed']);

            return $sale->fresh(['customer', 'branch', 'warehouse', 'register', 'registerSession', 'cashier', 'items.product', 'items.productVariant', 'payments', 'returns.refund']);
        });
    }
}
