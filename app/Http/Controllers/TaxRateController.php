<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaxRateRequest;
use App\Http\Requests\UpdateTaxRateRequest;
use App\Models\TaxClass;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', TaxRate::class);
        $taxRates = TaxRate::query()->with('taxClass')->latest()->paginate(15);

        return view('tax-rates.index', compact('taxRates'));
    }

    public function create(): View
    {
        $this->authorize('create', TaxRate::class);
        $taxClasses = TaxClass::query()->where('status', 'Active')->orderBy('name')->get();

        return view('tax-rates.create', compact('taxClasses'));
    }

    public function store(StoreTaxRateRequest $request): RedirectResponse
    {
        $this->authorize('create', TaxRate::class);
        TaxRate::create($request->validated());

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate created successfully.');
    }

    public function show(TaxRate $taxRate): View
    {
        $this->authorize('view', $taxRate);
        $taxRate->load('taxClass');

        return view('tax-rates.show', compact('taxRate'));
    }

    public function edit(TaxRate $taxRate): View
    {
        $this->authorize('update', $taxRate);
        $taxClasses = TaxClass::query()->where('status', 'Active')->orderBy('name')->get();

        return view('tax-rates.edit', compact('taxRate', 'taxClasses'));
    }

    public function update(UpdateTaxRateRequest $request, TaxRate $taxRate): RedirectResponse
    {
        $this->authorize('update', $taxRate);
        $taxRate->update($request->validated());

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate updated successfully.');
    }

    public function destroy(TaxRate $taxRate): RedirectResponse
    {
        $this->authorize('delete', $taxRate);
        $taxRate->delete();

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', TaxRate::class);
        $taxRates = TaxRate::onlyTrashed()->with('taxClass')->paginate(15);

        return view('tax-rates.trash', compact('taxRates'));
    }

    public function restore(int $taxRate): RedirectResponse
    {
        $taxRate = TaxRate::onlyTrashed()->findOrFail($taxRate);
        $this->authorize('restore', $taxRate);
        $taxRate->restore();

        return redirect()->route('tax-rates.trash')->with('success', 'Tax rate restored successfully.');
    }

    public function forceDelete(int $taxRate): RedirectResponse
    {
        $taxRate = TaxRate::onlyTrashed()->findOrFail($taxRate);
        $this->authorize('forceDelete', $taxRate);
        $taxRate->forceDelete();

        return redirect()->route('tax-rates.trash')->with('success', 'Tax rate permanently deleted.');
    }
}
