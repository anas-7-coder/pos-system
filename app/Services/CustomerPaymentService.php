<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use App\Models\CustomerPayment;
use App\Models\CustomerPaymentAllocation;
use App\Models\RegisterSession;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerPaymentService
{
    public function __construct(protected CashMovementService $cashMovementService) {}

    public function currentSaleOutstanding(Sale $sale): float
    {
        $directPayments = (float) $sale->payments()
            ->where('status', 'posted')
            ->sum('amount');

        $customerPayments = (float) $sale
            ->customerPaymentAllocations()
            ->sum('amount');

        return round(max((float) $sale->grand_total - $directPayments - $customerPayments, 0), 2);
    }

    public function outstandingSales(Customer $customer)
    {
        return Sale::query()
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['completed', 'partially_refunded'])
            ->with(['payments', 'customerPaymentAllocations'])
            ->orderBy('sale_date')
            ->get()
            ->map(function (Sale $sale) {

                $directPayments = (float) $sale->payments
                    ->where('status', 'posted')
                    ->sum('amount');

                $customerPayments = (float) $sale
                    ->customerPaymentAllocations
                    ->sum('amount');

                $sale->outstanding_amount = round(max((float) $sale->grand_total - $directPayments - $customerPayments, 0), 2);

                return $sale;
            })
            ->filter(fn ($sale) => $sale->outstanding_amount > 0)
            ->values();
    }

    // public function create(Customer $customer, array $data, User $user): CustomerPayment
    // {
    //     return DB::transaction(function () use ($customer, $data, $user)
    //     {
    //         $customer = Customer::query()
    //             ->lockForUpdate()
    //             ->findOrFail($customer->id);

    //         if ($customer->status !== 'Active') {
    //             throw ValidationException::withMessages(['customer' => 'The customer is not active.']);
    //         }

    //         $currentBalance = app(CustomerReceivableService::class)->currentBalance($customer);

    //         $amount = round((float) $data['amount'], 2);

    //         if ($amount > $currentBalance) {
    //             throw ValidationException::withMessages(['amount' => 'Payment cannot exceed the customer outstanding balance.']);
    //         }

    //         $paymentMethod   = $data['payment_method'];
    //         $registerSession = null;

    //         if ($paymentMethod === 'cash') {
    //             $registerSession = RegisterSession::query()
    //                 ->lockForUpdate()
    //                 ->findOrFail($data['register_session_id']);

    //             if ($registerSession->status !== 'Open') {
    //                 throw ValidationException::withMessages(['register_session_id' => 'The register session is not open.']);
    //             }

    //             if (!$user->hasRole('super_admin') && (int) $registerSession->user_id !== (int) $user->id)
    //             {
    //                 throw ValidationException::withMessages(['register_session_id' => 'You can only collect cash in your own register session.']);
    //             }

    //             // if ( (int) $registerSession->branch_id !== (int) $customer->branches()->first()?->id)
    //             // {
    //             //     /*
    //             //      * Branch validation is intentionally handled below
    //             //      * from the selected session.
    //             //      */
    //             // }

    //             $receivedAmount = round( (float) ($data['received_amount'] ?? 0), 2 );

    //             if ($receivedAmount < $amount) {
    //                 throw ValidationException::withMessages(['received_amount' => 'Received cash cannot be less than the payment amount.']);
    //             }

    //             $changeAmount = round($receivedAmount - $amount, 2);
    //         } else {
    //             $receivedAmount = $amount;
    //             $changeAmount   = 0;
    //         }

    //         if (!$user->hasRole('super_admin')) {
    //             $branchIds = $user->branches()
    //                 ->pluck('branches.id');

    //             if ($registerSession && !$branchIds->contains($registerSession->branch_id))
    //             {
    //                 throw ValidationException::withMessages(['register_session_id' => 'You do not have access to this branch.']);
    //             }
    //         }

    //         $payment = CustomerPayment::create([
    //             'customer_id'         => $customer->id,
    //             // 'branch_id'           => $registerSession?->branch_id ?? $customer->branches()->value('branches.id'),
    //             'branch_id'           => $registerSession?->branch_id,
    //             'register_session_id' => $registerSession?->id,
    //             'created_by'          => $user->id,
    //             'payment_number'      => 'CP-' . now()->format('Ymd') . '-' . str()->upper(str()->random(6)),
    //             'payment_method'      => $paymentMethod,
    //             'amount'              => $amount,
    //             'received_amount'     => $receivedAmount,
    //             'change_amount'       => $changeAmount,
    //             'reference'           => $data['reference'] ?? null,
    //             'paid_at'             => now(),
    //             'status'              => 'posted',
    //         ]);

    //         $allocations     = $data['allocations'] ?? [];
    //         $allocationTotal = 0;

    //         foreach ($allocations as $allocation) {

    //             $sale = Sale::query()
    //                 ->where('id', $allocation['sale_id'])
    //                 ->where('customer_id', $customer->id)
    //                 ->lockForUpdate()
    //                 ->first();

    //             if (!$sale) {
    //                 throw ValidationException::withMessages(['allocations' => 'One of the selected invoices does not belong to this customer.']);
    //             }

    //             $allocationAmount = round( (float) $allocation['amount'], 2 );
    //             $outstanding      = $this->currentSaleOutstanding($sale);

    //             if ($allocationAmount > $outstanding) {
    //                 throw ValidationException::withMessages(['allocations' => "Payment exceeds the outstanding balance of invoice {$sale->invoice_number}."]);
    //             }

    //             CustomerPaymentAllocation::create([
    //                 'customer_payment_id' => $payment->id,
    //                 'sale_id'             => $sale->id,
    //                 'amount'              => $allocationAmount,
    //             ]);

    //             $allocationTotal += $allocationAmount;
    //         }

    //         /*
    //          * When allocations are provided, they must
    //          * exactly match the payment amount.
    //          */
    //         if (!empty($allocations)) {
    //             if (round($allocationTotal, 2) !== round($amount, 2))
    //             {
    //                 throw ValidationException::withMessages(['allocations' => 'Allocated amounts must equal the payment amount.']);
    //             }
    //         }

    //         /*
    //          * Record the customer ledger credit.
    //          */
    //         CustomerLedgerEntry::create([
    //             'customer_id'         => $customer->id,
    //             // 'branch_id'           => $registerSession?->branch_id ?? $customer->branches()->value('branches.id'),
    //             'branch_id'           => $registerSession?->branch_id,
    //             'sale_id'             => null,
    //             'customer_payment_id' => $payment->id,
    //             'created_by'          => $user->id,
    //             'type'                => 'customer_payment',
    //             'debit'               => 0,
    //             'credit'              => $amount,
    //             'description'         => 'Customer payment ' . $payment->payment_number,
    //             'transaction_date'    => now(),
    //         ]);

    //         if ($paymentMethod === 'cash') {
    //             $this->cashMovementService->recordCustomerPayment($payment, $user);
    //         }

    //         return $payment->fresh(['customer', 'registerSession', 'createdBy', 'allocations.sale', 'ledgerEntry']);
    //     });
    // }

    public function create(Customer $customer, array $data, User $user): CustomerPayment
    {
        return DB::transaction(function () use ($customer, $data, $user) {

            $customer = Customer::query()
                ->lockForUpdate()
                ->findOrFail($customer->id);

            if ($customer->status !== 'Active') {
                throw ValidationException::withMessages(['customer' => 'The customer is not active.']);
            }

            $paymentMethod = $data['payment_method'] ?? null;

            if (! in_array($paymentMethod, ['cash', 'card', 'bank_transfer', 'mobile_wallet'], true)) {
                throw ValidationException::withMessages(['payment_method' => 'The selected payment method is invalid.']);
            }

            $amount = round((float) ($data['amount'] ?? 0), 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Payment amount must be greater than zero.']);
            }

            /*
            * Every customer payment must belong to a real branch.
            * The register session provides the branch context.
            */
            if (empty($data['register_session_id'])) {
                throw ValidationException::withMessages(['register_session_id' => 'A register session is required for customer payments.']);
            }

            $registerSession = RegisterSession::query()
                ->lockForUpdate()
                ->find($data['register_session_id']);

            if (! $registerSession) {
                throw ValidationException::withMessages(['register_session_id' => 'Register session not found.']);
            }

            if ($registerSession->status !== 'Open') {
                throw ValidationException::withMessages(['register_session_id' => 'The register session is not open.']);
            }

            if (! $user->hasRole('super_admin') && (int) $registerSession->user_id !== (int) $user->id
            ) {
                throw ValidationException::withMessages(['register_session_id' => 'You can only collect payments in your own register session.']);
            }

            if (! $user->hasRole('super_admin') && ! $user->hasBranchAccess($registerSession->branch_id)
            ) {
                throw ValidationException::withMessages(['register_session_id' => 'You do not have access to this branch.']);
            }

            $currentBalance = app(CustomerReceivableService::class)->currentBalance($customer);

            if ($amount > $currentBalance) {
                throw ValidationException::withMessages(['amount' => 'Payment cannot exceed the customer outstanding balance.']);
            }

            if ($paymentMethod === 'cash') {

                $receivedAmount = round((float) ($data['received_amount'] ?? 0), 2);

                if ($receivedAmount < $amount) {
                    throw ValidationException::withMessages(['received_amount' => 'Received cash cannot be less than the payment amount.']);
                }

                $changeAmount = round($receivedAmount - $amount, 2);
            } else {
                $receivedAmount = $amount;
                $changeAmount = 0;
            }

            $payment = CustomerPayment::create([
                'customer_id' => $customer->id,
                'branch_id' => $registerSession->branch_id,
                'register_session_id' => $registerSession->id,
                'created_by' => $user->id,
                'payment_number' => 'CP-'.now()->format('Ymd').'-'.str()->upper(str()->random(6)),
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'received_amount' => $receivedAmount,
                'change_amount' => $changeAmount,
                'reference' => $data['reference'] ?? null,
                'paid_at' => now(),
                'status' => 'posted',
            ]);

            $allocations = $data['allocations'] ?? [];
            $allocationTotal = 0;

            foreach ($allocations as $allocation) {
                $sale = Sale::query()
                    ->whereKey($allocation['sale_id'])
                    ->where('customer_id', $customer->id)
                    ->whereIn('status', ['completed', 'partially_refunded'])
                    ->lockForUpdate()
                    ->first();

                if (! $sale) {
                    throw ValidationException::withMessages(['allocations' => 'One of the selected invoices is not a valid outstanding sale for this customer.']);
                }

                $allocationAmount = round((float) ($allocation['amount'] ?? 0), 2);

                if ($allocationAmount <= 0) {
                    throw ValidationException::withMessages(['allocations' => 'Allocation amount must be greater than zero.']);
                }

                $outstanding = $this->currentSaleOutstanding($sale);

                if ($allocationAmount > $outstanding) {
                    throw ValidationException::withMessages(['allocations' => "Payment exceeds the outstanding balance of invoice {$sale->invoice_number}.",
                    ]);
                }

                CustomerPaymentAllocation::create([
                    'customer_payment_id' => $payment->id,
                    'sale_id' => $sale->id,
                    'amount' => $allocationAmount,
                ]);

                $allocationTotal += $allocationAmount;
            }

            /*
            * When allocations are provided, they must exactly
            * equal the customer payment amount.
            *
            * No allocations means the payment remains
            * unallocated customer credit.
            */
            if (! empty($allocations)) {
                if (round($allocationTotal, 2) !== round($amount, 2)) {
                    throw ValidationException::withMessages(['allocations' => 'Allocated amounts must equal the payment amount.']);
                }
            }

            CustomerLedgerEntry::create([
                'customer_id' => $customer->id,
                'branch_id' => $registerSession->branch_id,
                'sale_id' => null,
                'customer_payment_id' => $payment->id,
                'created_by' => $user->id,
                'type' => 'customer_payment',
                'debit' => 0,
                'credit' => $amount,
                'description' => 'Customer payment '.$payment->payment_number,
                'transaction_date' => now(),
            ]);

            if ($paymentMethod === 'cash') {
                $this->cashMovementService->recordCustomerPayment(payment: $payment, user: $user);
            }

            return $payment->fresh(['customer', 'registerSession', 'createdBy', 'allocations.sale', 'ledgerEntry']);
        });
    }
}
