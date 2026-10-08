<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Customer::class);
        $customers = Customer::query()->with('customerGroup')->latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        $this->authorize('create', Customer::class);
        $customerGroups = CustomerGroup::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('customers.create', compact('customerGroups'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        try {
            Customer::create($request->validated());

            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to create customer.');
        }
    }

    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);
        $customerGroups = CustomerGroup::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('customers.edit', compact('customer', 'customerGroups'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        try {
            $customer->update($request->validated());

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update customer.');
        }
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        try {
            $customer->delete();

            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete customer.');
        }
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Customer::class);
        $customers = Customer::onlyTrashed()->with('customerGroup')->paginate(15);

        return view('customers.trash', compact('customers'));
    }

    public function restore(int $customer): RedirectResponse
    {
        $customerModel = Customer::onlyTrashed()->findOrFail($customer);
        $this->authorize('restore', $customerModel);

        try {
            $customerModel->restore();

            return back()->with('success', 'Customer restored successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to restore customer.');
        }
    }

    public function forceDelete(int $customer): RedirectResponse
    {
        $customerModel = Customer::onlyTrashed()->findOrFail($customer);
        $this->authorize('forceDelete', $customerModel);

        try {
            $customerModel->forceDelete();

            return back()->with('success', 'Customer permanently deleted.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to permanently delete customer.');
        }
    }
}
