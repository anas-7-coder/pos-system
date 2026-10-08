<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);
        $companies = Company::query()->latest()->paginate(15);

        return view('companies.index', compact('companies'));
    }

    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $this->authorize('create', Company::class);
        Company::create($request->validated());

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function show(Company $company): View
    {
        $this->authorize('view', $company);

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        $this->authorize('update', $company);

        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        $this->authorize('update', $company);
        $company->update($request->validated());

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $this->authorize('delete', $company);
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Company::class);
        $companies = Company::onlyTrashed()->paginate(15);

        return view('companies.trash', compact('companies'));
    }

    public function restore(int $company): RedirectResponse
    {
        $company = Company::onlyTrashed()->findOrFail($company);
        $this->authorize('restore', $company);
        $company->restore();

        return redirect()->route('companies.trash')->with('success', 'Company restored successfully.');
    }

    public function forceDelete(int $company): RedirectResponse
    {
        $company = Company::onlyTrashed()->findOrFail($company);
        $this->authorize('forceDelete', $company);
        $company->forceDelete();

        return redirect()->route('companies.trash')->with('success', 'Company permanently deleted.');
    }
}
