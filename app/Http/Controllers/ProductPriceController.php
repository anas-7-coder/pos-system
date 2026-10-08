<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Requests\UpdateProductPriceRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Services\ProductPriceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductPriceController extends Controller
{
    public function __construct(private ProductPriceService $productPriceService) {}

    public function index(Product $product): View
    {
        $this->authorize('viewAny', ProductPrice::class);
        $prices = $product->prices()->with(['productVariant', 'branch'])->paginate(15);

        return view('products.prices.index', compact('product', 'prices'));
    }

    public function create(Product $product): View
    {
        $this->authorize('create', ProductPrice::class);
        $product->load('variants');
        $branches = Branch::query()->where('status', 'Active')->orderBy('name')->get();

        return view('products.prices.create', compact('product', 'branches'));
    }

    public function store(StoreProductPriceRequest $request, Product $product): RedirectResponse
    {
        try {
            $this->productPriceService->create($product, $request->validated());

            return redirect()->route('products.prices.index', $product)->with('success', 'Product price created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to create product price.');
        }
    }

    public function show(Product $product, ProductPrice $productPrice): View
    {
        $this->authorize('view', $productPrice);
        abort_unless($productPrice->product_id === $product->id, 404);
        $productPrice->load(['productVariant', 'branch']);

        return view('products.prices.show', compact('product', 'productPrice'));
    }

    public function edit(Product $product, ProductPrice $productPrice): View
    {
        $this->authorize('update', $productPrice);
        abort_unless($productPrice->product_id === $product->id, 404);
        $product->load('variants');
        $branches = Branch::query()->where('status', 'Active')->orderBy('name')->get();

        return view('products.prices.edit', compact('product', 'productPrice', 'branches'));
    }

    public function update(UpdateProductPriceRequest $request, Product $product, ProductPrice $productPrice): RedirectResponse
    {
        abort_unless($productPrice->product_id === $product->id, 404);

        try {
            $this->productPriceService->update($productPrice, $request->validated());

            return redirect()->route('products.prices.index', $product)->with('success', 'Product price updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update product price.');
        }
    }

    public function destroy(Product $product, ProductPrice $productPrice): RedirectResponse
    {
        $this->authorize('delete', $productPrice);
        abort_unless($productPrice->product_id === $product->id, 404);

        try {
            $this->productPriceService->delete($productPrice);

            return redirect()->route('products.prices.index', $product)->with('success', 'Product price deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete product price.');
        }
    }

    public function trash(Product $product): View
    {
        $this->authorize('viewTrash', ProductPrice::class);
        $prices = $product->prices()->onlyTrashed()->with(['productVariant', 'branch'])->paginate(15);

        return view('products.prices.trash', compact('product', 'prices'));
    }

    public function restore(Product $product, int $productPrice): RedirectResponse
    {
        $price = ProductPrice::onlyTrashed()->where('product_id', $product->id)->findOrFail($productPrice);
        $this->authorize('restore', $price);

        try {
            $this->productPriceService->restore($price);

            return back()->with('success', 'Product price restored successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to restore product price.');
        }
    }

    public function forceDelete(Product $product, int $productPrice): RedirectResponse
    {
        $price = ProductPrice::onlyTrashed()->where('product_id', $product->id)->findOrFail($productPrice);
        $this->authorize('forceDelete', $price);

        try {
            $price->forceDelete();

            return back()->with('success', 'Product price permanently deleted.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to permanently delete product price.');
        }
    }
}
