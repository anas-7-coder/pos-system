<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerReceivableService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\CustomerLedgerEntry;

class CustomerReceivableController extends Controller
{
    public function index(CustomerReceivableService $customerReceivableService): View
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->hasPermission('customer_receivables.view'), 403);

        $customers = Customer::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->paginate(20);

        CustomerLedgerEntry::query()
            ->whereIn('branch_id', $user->accessibleBranchIds());
    
        $balances = [];

        foreach ($customers as $customer) {
            $balances[$customer->id] = $customerReceivableService->currentBalance($customer);
        }

        return view('customer-receivables.index', compact('customers', 'balances'));
    }

    public function show(Customer $customer, CustomerReceivableService $customerReceivableService): View
    {
        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->hasPermission('customer_receivables.view'), 403);
        $customer->load(['ledgerEntries.sale', 'ledgerEntries.createdBy']);
        $balance = $customerReceivableService->currentBalance($customer);

        return view('customer-receivables.show', compact('customer', 'balance'));
    }
}
