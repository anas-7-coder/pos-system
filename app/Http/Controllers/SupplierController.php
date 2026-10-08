<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Supplier::class);
        $suppliers = Supplier::query()->latest()->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        $this->authorize('create', Supplier::class);

        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        try {
            Supplier::create($request->validated());

            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to create supplier.');
        }
    }

    public function show(Supplier $supplier): View
    {
        $this->authorize('view', $supplier);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        $this->authorize('update', $supplier);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        try {
            $supplier->update($request->validated());

            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update supplier.');
        }
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->authorize('delete', $supplier);

        try {
            $supplier->delete();

            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete supplier.');
        }
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Supplier::class);
        $suppliers = Supplier::onlyTrashed()->paginate(15);

        return view('suppliers.trash', compact('suppliers'));
    }

    public function restore(int $supplier): RedirectResponse
    {
        $supplierModel = Supplier::onlyTrashed()->findOrFail($supplier);
        $this->authorize('restore', $supplierModel);

        try {
            $supplierModel->restore();

            return back()->with('success', 'Supplier restored successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to restore supplier.');
        }
    }

    public function forceDelete(int $supplier): RedirectResponse
    {
        $supplierModel = Supplier::onlyTrashed()->findOrFail($supplier);
        $this->authorize('forceDelete', $supplierModel);

        try {
            $supplierModel->forceDelete();

            return back()->with('success', 'Supplier permanently deleted.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to permanently delete supplier.');
        }
    }
}
