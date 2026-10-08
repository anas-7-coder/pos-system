<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Brand::class);
        $brands = Brand::query()->latest()->paginate(15);

        return view('brands.index', compact('brands'));
    }

    public function create(): View
    {
        $this->authorize('create', Brand::class);

        return view('brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $this->authorize('create', Brand::class);
        Brand::create($request->validated());

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand): View
    {
        $this->authorize('view', $brand);

        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand): View
    {
        $this->authorize('update', $brand);

        return view('brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $this->authorize('update', $brand);
        $brand->update($request->validated());

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->authorize('delete', $brand);
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Brand::class);
        $brands = Brand::onlyTrashed()->paginate(15);

        return view('brands.trash', compact('brands'));
    }

    public function restore(int $brand): RedirectResponse
    {
        $brand = Brand::onlyTrashed()->findOrFail($brand);
        $this->authorize('restore', $brand);
        $brand->restore();

        return redirect()->route('brands.trash')->with('success', 'Brand restored successfully.');
    }

    public function forceDelete(int $brand): RedirectResponse
    {
        $brand = Brand::onlyTrashed()->findOrFail($brand);
        $this->authorize('forceDelete', $brand);
        $brand->forceDelete();

        return redirect()->route('brands.trash')->with('success', 'Brand permanently deleted.');
    }
}
