<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Branch::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branches = Branch::query()->with('company')->whereIn('id', $user->accessibleBranchIds())->latest()->paginate(15);

        return view('branches.index', compact('branches'));
    }

    public function create(): View
    {
        $this->authorize('create', Branch::class);
        $companies = Company::query()->where('status', 'Active')->orderBy('name')->get();

        return view('branches.create', compact('companies'));
    }

    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $this->authorize('create', Branch::class);
        Branch::create($request->validated());

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch): View
    {
        $this->authorize('view', $branch);
        $branch->load('company');

        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch): View
    {
        $this->authorize('update', $branch);
        $companies = Company::query()->where('status', 'Active')->orderBy('name')->get();

        return view('branches.edit', compact('branch', 'companies'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $this->authorize('update', $branch);
        $branch->update($request->validated());

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->authorize('delete', $branch);
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Branch::class);
        $branches = Branch::onlyTrashed()->with('company')->paginate(15);

        return view('branches.trash', compact('branches'));
    }

    public function restore(int $branch): RedirectResponse
    {
        $branch = Branch::onlyTrashed()->findOrFail($branch);
        $this->authorize('restore', $branch);
        $branch->restore();

        return redirect()->route('branches.trash')->with('success', 'Branch restored successfully.');
    }

    public function forceDelete(int $branch): RedirectResponse
    {
        $branch = Branch::onlyTrashed()->findOrFail($branch);
        $this->authorize('forceDelete', $branch);
        $branch->forceDelete();

        return redirect()->route('branches.trash')->with('success', 'Branch permanently deleted.');
    }
}
