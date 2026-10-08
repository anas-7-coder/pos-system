<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductBarcodeRequest;
use App\Http\Requests\UpdateProductBarcodeRequest;
use App\Models\Product;
use App\Models\ProductBarcode;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductBarcodeController extends Controller
{
    public function index(Product $product): View
    {
        $this->authorize('viewAny', ProductBarcode::class);
        $barcodes = $product->barcodes()->paginate(15);

        return view('products.barcodes.index', compact('product', 'barcodes'));
    }

    public function create(Product $product): View
    {
        $this->authorize('create', ProductBarcode::class);

        return view('products.barcodes.create', compact('product'));
    }

    public function store(StoreProductBarcodeRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('create', ProductBarcode::class);
        $validated = $request->validated();

        if ($validated['is_primary']) {
            $product->barcodes()->update(['is_primary' => false]);
        }

        /*
         * First barcode automatically becomes primary.
         */
        if (! $product->barcodes()->exists()) {
            $validated['is_primary'] = true;
        }

        $product->barcodes()->create($validated);

        return redirect()->route('products.barcodes.index', $product)->with('success', 'Barcode added successfully.');
    }

    public function edit(ProductBarcode $productBarcode): View
    {
        $this->authorize('update', $productBarcode);
        $productBarcode->load('product');

        return view('products.barcodes.edit', compact('productBarcode'));
    }

    public function update(UpdateProductBarcodeRequest $request, ProductBarcode $productBarcode): RedirectResponse
    {
        $this->authorize('update', $productBarcode);
        $validated = $request->validated();

        if ($validated['is_primary']) {
            $productBarcode->product->barcodes()->where('id', '!=', $productBarcode->id)->update(['is_primary' => false]);
        }

        $productBarcode->update($validated);

        return redirect()->route('products.barcodes.index', $productBarcode->product)->with('success', 'Barcode updated successfully.');
    }

    public function destroy(ProductBarcode $productBarcode): RedirectResponse
    {
        $this->authorize('delete', $productBarcode);
        $product = $productBarcode->product;
        $wasPrimary = $productBarcode->is_primary;
        $productBarcode->delete();

        /*
         * If the primary barcode was deleted,
         * automatically select another barcode.
         */
        if ($wasPrimary) {
            $newPrimary = $product->barcodes()->orderBy('id')->first();

            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return redirect()->route('products.barcodes.index', $product)->with('success', 'Barcode moved to trash successfully.');
    }

    public function trash(Product $product): View
    {
        $this->authorize('viewTrash', ProductBarcode::class);

        $barcodes = ProductBarcode::onlyTrashed()->where('product_id', $product->id)->paginate(15);

        return view('products.barcodes.trash', compact('product', 'barcodes'));
    }

    public function restore(int $productBarcode): RedirectResponse
    {
        $productBarcode = ProductBarcode::onlyTrashed()->findOrFail($productBarcode);
        $this->authorize('restore', $productBarcode);
        $product = $productBarcode->product;
        $productBarcode->restore();

        /*
         * If no primary barcode exists, make this one primary.
         */
        $hasPrimary = $product->barcodes()->where('is_primary', true)->exists();

        if (! $hasPrimary) {
            $productBarcode->update(['is_primary' => true]);
        }

        return redirect()->route('products.barcodes.trash', $product)->with('success', 'Barcode restored successfully.');
    }

    public function forceDelete(int $productBarcode): RedirectResponse
    {
        $productBarcode = ProductBarcode::onlyTrashed()->findOrFail($productBarcode);
        $this->authorize('forceDelete', $productBarcode);
        $product = $productBarcode->product;
        $productBarcode->forceDelete();

        return redirect()->route('products.barcodes.trash', $product)->with('success', 'Barcode permanently deleted.');
    }
}
