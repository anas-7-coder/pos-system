<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Models\CustomerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerGroupController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', CustomerGroup::class);
        $customerGroups = CustomerGroup::query()->withCount('customers')->latest()->paginate(15);

        return view('customer-groups.index', compact('customerGroups'));
    }

    public function create(): View
    {
        $this->authorize('create', CustomerGroup::class);

        return view('customer-groups.create');
    }

    public function store(StoreCustomerGroupRequest $request): RedirectResponse
    {
        try {
            CustomerGroup::create($request->validated());

            return redirect()->route('customer-groups.index')->with('success', 'Customer group created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to create customer group.');
        }
    }

    public function show(CustomerGroup $customerGroup): View
    {
        $this->authorize('view', $customerGroup);
        $customerGroup->loadCount('customers');

        return view('customer-groups.show', compact('customerGroup'));
    }

    public function edit(CustomerGroup $customerGroup): View
    {
        $this->authorize('update', $customerGroup);

        return view('customer-groups.edit', compact('customerGroup'));
    }

    public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup): RedirectResponse
    {
        try {
            $customerGroup->update($request->validated());

            return redirect()->route('customer-groups.index')->with('success', 'Customer group updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update customer group.');
        }
    }

    public function destroy(CustomerGroup $customerGroup): RedirectResponse
    {
        $this->authorize('delete', $customerGroup);

        try {
            $customerGroup->delete();

            return redirect()->route('customer-groups.index')->with('success', 'Customer group deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete customer group.');
        }
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', CustomerGroup::class);
        $customerGroups = CustomerGroup::onlyTrashed()->withCount('customers')->paginate(15);

        return view('customer-groups.trash', compact('customerGroups'));
    }

    public function restore(int $customerGroup): RedirectResponse
    {
        $group = CustomerGroup::onlyTrashed()->findOrFail($customerGroup);
        $this->authorize('restore', $group);

        try {
            $group->restore();

            return back()->with('success', 'Customer group restored successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to restore customer group.');
        }
    }

    public function forceDelete(int $customerGroup): RedirectResponse
    {
        $group = CustomerGroup::onlyTrashed()->findOrFail($customerGroup);
        $this->authorize('forceDelete', $group);

        try {
            $group->forceDelete();

            return back()->with('success', 'Customer group permanently deleted.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to permanently delete customer group.');
        }
    }
}
