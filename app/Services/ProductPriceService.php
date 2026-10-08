<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductPriceService
{
    public function create(Product $product, array $data): ProductPrice
    {
        $this->validateBusinessRules($product, $data);

        return DB::transaction(function () use ($product, $data) {
            $data['product_id'] = $product->id;

            return ProductPrice::create($data);
        });
    }

    public function update(ProductPrice $productPrice, array $data): ProductPrice
    {
        $this->validateBusinessRules($productPrice->product, $data, $productPrice->id);

        return DB::transaction(function () use ($productPrice, $data) {
            $productPrice->update($data);

            return $productPrice->fresh();
        });
    }

    public function delete(ProductPrice $productPrice): void
    {
        DB::transaction(function () use ($productPrice) {
            $productPrice->delete();
        });
    }

    public function restore(ProductPrice $productPrice): void
    {
        DB::transaction(function () use ($productPrice) {
            $this->validateBusinessRules(
                $productPrice->product,
                $productPrice->toArray(),
                $productPrice->id
            );

            $productPrice->restore();
        });
    }

    private function validateBusinessRules(Product $product, array $data, ?int $ignoreId = null): void
    {
        /*
         * If a variant is selected, verify that it belongs
         * to the selected product.
         */
        if (! empty($data['product_variant_id'])) {
            $variantBelongsToProduct = $product->variants()
                ->whereKey($data['product_variant_id'])
                ->exists();

            if (! $variantBelongsToProduct) {
                throw ValidationException::withMessages([
                    'product_variant_id' => 'The selected variant does not belong to this product.',
                ]);
            }
        }

        /*
         * Check for overlapping date ranges.
         */
        $query = ProductPrice::query()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $data['product_variant_id'] ?? null)
            ->where('branch_id', $data['branch_id'] ?? null)
            ->where('price_type', $data['price_type'])
            ->where('status', 'active')
            ->where(function ($query) use ($data) {
                $newStart = $data['effective_from'];
                $newEnd = $data['effective_to'];

                $query->where(function ($query) use ($newEnd) {
                    $query->whereNull('effective_to')
                        ->where('effective_from', '<=', $newEnd ?? '9999-12-31');
                })
                    ->orWhere(function ($query) use ($newStart, $newEnd) {
                        $query->whereNotNull('effective_to')
                            ->where('effective_to', '>=', $newStart);

                        if ($newEnd) {
                            $query->where('effective_from', '<=', $newEnd);
                        }
                    });
            });

        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'effective_from' => 'This pricing period overlaps with an existing price for the same product, variant, branch, and price type.',
            ]);
        }
    }
}
