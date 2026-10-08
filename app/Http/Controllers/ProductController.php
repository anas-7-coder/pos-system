<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\TaxClass;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Product::class);
        $products = Product::query()->with(['category', 'brand', 'unit', 'taxClass'])->latest()->paginate(15);

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $this->authorize('create', Product::class);

        $categories = Category::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $units = Unit::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $taxClasses = TaxClass::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories', 'brands', 'units', 'taxClasses'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);
        $validated = $request->validated();

        /*
         * A service does not normally need inventory tracking.
         */
        if ($validated['product_type'] === 'service') {
            $validated['track_inventory'] = false;
            $validated['allow_negative_stock'] = false;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $this->authorize('view', $product);
        $product->load(['category', 'brand', 'unit', 'taxClass']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        $categories = Category::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $brands = Brand::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $units = Unit::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $taxClasses = TaxClass::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'taxClasses'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        $validated = $request->validated();

        if ($validated['product_type'] === 'service') {
            $validated['track_inventory'] = false;
            $validated['allow_negative_stock'] = false;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Product::class);
        $products = Product::onlyTrashed()->with(['category', 'brand', 'unit', 'taxClass'])->paginate(15);

        return view('products.trash', compact('products'));
    }

    public function restore(int $product): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($product);
        $this->authorize('restore', $product);
        $product->restore();

        return redirect()->route('products.trash')->with('success', 'Product restored successfully.');
    }

    public function forceDelete(int $product): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($product);
        $this->authorize('forceDelete', $product);
        $product->forceDelete();

        return redirect()->route('products.trash')->with('success', 'Product permanently deleted.');
    }
}
