<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaxClassRequest;
use App\Http\Requests\UpdateTaxClassRequest;
use App\Models\TaxClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxClassController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TaxClass::class);
        $taxClasses = TaxClass::query()->latest()->paginate(15);

        return view('tax-classes.index', compact('taxClasses'));
    }

    public function create(): View
    {
        $this->authorize('create', TaxClass::class);

        return view('tax-classes.create');
    }

    public function store(StoreTaxClassRequest $request): RedirectResponse
    {
        $this->authorize('create', TaxClass::class);
        TaxClass::create($request->validated());

        return redirect()->route('tax-classes.index')->with('success', 'Tax class created successfully.');
    }

    public function show(TaxClass $taxClass): View
    {
        $this->authorize('view', $taxClass);
        $taxClass->load('taxRates');

        return view('tax-classes.show', compact('taxClass'));
    }

    public function edit(TaxClass $taxClass): View
    {
        $this->authorize('update', $taxClass);

        return view('tax-classes.edit', compact('taxClass'));
    }

    public function update(UpdateTaxClassRequest $request, TaxClass $taxClass): RedirectResponse
    {
        $this->authorize('update', $taxClass);
        $taxClass->update($request->validated());

        return redirect()->route('tax-classes.index')->with('success', 'Tax class updated successfully.');
    }

    public function destroy(TaxClass $taxClass): RedirectResponse
    {
        $this->authorize('delete', $taxClass);
        $taxClass->delete();

        return redirect()->route('tax-classes.index')->with('success', 'Tax class moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', TaxClass::class);
        $taxClasses = TaxClass::onlyTrashed()->paginate(15);

        return view('tax-classes.trash', compact('taxClasses'));
    }

    public function restore(int $taxClass): RedirectResponse
    {
        $taxClass = TaxClass::onlyTrashed()->findOrFail($taxClass);
        $this->authorize('restore', $taxClass);
        $taxClass->restore();

        return redirect()->route('tax-classes.trash')->with('success', 'Tax class restored successfully.');
    }

    public function forceDelete(int $taxClass): RedirectResponse
    {
        $taxClass = TaxClass::onlyTrashed()->findOrFail($taxClass);
        $this->authorize('forceDelete', $taxClass);
        $taxClass->forceDelete();

        return redirect()->route('tax-classes.trash')->with('success', 'Tax class permanently deleted.');
    }
}
