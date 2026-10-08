<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        $this->authorize('viewAny', ProductVariant::class);
        $variants = $product->variants()->latest()->paginate(15);

        return view('products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product): View
    {
        $this->authorize('create', ProductVariant::class);

        return view('products.variants.create', compact('product'));
    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('create', ProductVariant::class);
        $validated = $request->validated();

        if ($validated['is_default']) {
            $product->variants()->update(['is_default' => false]);
        }

        if (! $product->variants()->exists()) {
            $validated['is_default'] = true;
        }

        $product->variants()->create($validated);

        return redirect()->route('products.variants.index', $product)->with('success', 'Product variant created successfully.');
    }

    public function show(ProductVariant $productVariant): View
    {
        $this->authorize('view', $productVariant);
        $productVariant->load(['product', 'barcodes']);

        return view('products.variants.show', compact('productVariant'));
    }

    public function edit(ProductVariant $productVariant): View
    {
        $this->authorize('update', $productVariant);
        $productVariant->load('product');

        return view('products.variants.edit', compact('productVariant'));
    }

    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant): RedirectResponse
    {
        $this->authorize('update', $productVariant);
        $validated = $request->validated();

        if ($validated['is_default']) {
            $productVariant->product
                ->variants()
                ->where('id', '!=', $productVariant->id)
                ->update(['is_default' => false]);
        }

        $productVariant->update($validated);

        return redirect()->route('products.variants.index', $productVariant->product)->with('success', 'Product variant updated successfully.');
    }

    public function destroy(ProductVariant $productVariant): RedirectResponse
    {
        $this->authorize('delete', $productVariant);
        $product = $productVariant->product;
        $wasDefault = $productVariant->is_default;
        $productVariant->delete();

        if ($wasDefault) {
            $newDefault = $product->variants()
                ->where('status', 'Active')
                ->orderBy('id')
                ->first();

            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return redirect()->route('products.variants.index', $product)->with('success', 'Product variant moved to trash successfully.');
    }

    public function trash(Product $product): View
    {
        $this->authorize('viewTrash', ProductVariant::class);
        $variants = ProductVariant::onlyTrashed()
            ->where('product_id', $product->id)
            ->paginate(15);

        return view('products.variants.trash', compact('product', 'variants'));
    }

    public function restore(int $productVariant): RedirectResponse
    {
        $productVariant = ProductVariant::onlyTrashed()->findOrFail($productVariant);
        $this->authorize('restore', $productVariant);
        $product = $productVariant->product;
        $productVariant->restore();
        $hasDefault = $product->variants()->where('is_default', true)->exists();

        if (! $hasDefault) {
            $productVariant->update(['is_default' => true]);
        }

        return redirect()->route('products.variants.trash', $product)->with('success', 'Product variant restored successfully.');
    }

    public function forceDelete(int $productVariant): RedirectResponse
    {
        $productVariant = ProductVariant::onlyTrashed()->findOrFail($productVariant);
        $this->authorize('forceDelete', $productVariant);
        $product = $productVariant->product;
        $productVariant->forceDelete();

        return redirect()->route('products.variants.trash', $product)->with('success', 'Product variant permanently deleted.');
    }
}
