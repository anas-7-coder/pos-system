<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerPaymentRequest;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\RegisterSession;
use App\Models\User;
use App\Services\CustomerPaymentService;
use App\Services\CustomerReceivableService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CustomerPaymentController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', CustomerPayment::class);

        $payments = CustomerPayment::query()
            ->with(['customer', 'registerSession.register', 'createdBy', 'allocations.sale'])
            ->latest('paid_at')
            ->paginate(20);

        return view('customer-payments.index', compact('payments'));
    }

    public function create(Customer $customer, CustomerPaymentService $customerPaymentService): View
    {
        $this->authorize('create', [CustomerPayment::class, $customer]);
        $outstandingSales = $customerPaymentService->outstandingSales($customer);

        /** @var User $user */
        $user = Auth::user();

        $registerSessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->where('user_id', Auth::id())
            ->whereIn('branch_id', $user->branches()
                ->pluck('branches.id')
            )
            ->get();

        $currentBalance = app(CustomerReceivableService::class)->currentBalance($customer);

        return view('customer-payments.create', compact('customer', 'outstandingSales', 'registerSessions', 'currentBalance'));
    }

    public function store(StoreCustomerPaymentRequest $request, Customer $customer, CustomerPaymentService $customerPaymentService): RedirectResponse
    {
        $this->authorize('create', [CustomerPayment::class, $customer]);

        try {
            $payment = $customerPaymentService->create($customer, $request->validated(), Auth::user());

            return redirect()->route('customers.receivables', $customer)->with('success', "Customer payment {$payment->payment_number} recorded successfully.");
        } catch (\Throwable $e) {
            Log::error(
                'Customer payment creation failed',
                [
                    'customer_id' => $customer->id,
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                ]
            );

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(CustomerPayment $customerPayment): View
    {
        $this->authorize('view', $customerPayment);
        $customerPayment->load(['customer', 'branch', 'registerSession.register', 'createdBy', 'allocations.sale', 'ledgerEntry']);

        return view('customer-payments.show', compact('customerPayment'));
    }
}
