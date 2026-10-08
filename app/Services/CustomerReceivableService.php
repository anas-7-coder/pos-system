<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerReceivableService
{
    public function currentBalance(Customer $customer): float
    {
        $debit = (float) $customer->ledgerEntries()->sum('debit');
        $credit = (float) $customer->ledgerEntries()->sum('credit');

        return round((float) $customer->opening_balance + $debit - $credit, 2);
    }

    public function availableCredit(Customer $customer): ?float
    {
        $creditLimit = (float) $customer->credit_limit;

        /*
         * A credit limit of 0 means credit sales are not allowed.
         */
        if ($creditLimit <= 0) {
            return 0;
        }

        $balance = $this->currentBalance($customer);

        return round(max($creditLimit - $balance, 0), 2);
    }

    public function validateCreditSale(Customer $customer, float $amount): void
    {
        if ($customer->status !== 'Active') {
            throw ValidationException::withMessages(['customer' => 'The customer is not active.']);
        }

        $creditLimit = (float) $customer->credit_limit;

        if ($creditLimit <= 0) {
            throw ValidationException::withMessages(['customer' => 'This customer does not have a credit limit.']);
        }

        $currentBalance = $this->currentBalance($customer);

        $newBalance = round($currentBalance + $amount, 2);

        if ($newBalance > $creditLimit) {
            throw ValidationException::withMessages(['customer' => 'This sale would exceed the customer credit limit.']);
        }
    }

    // public function recordSaleCredit(Sale $sale, float $amount, User $user): CustomerLedgerEntry
    // {
    //     return DB::transaction(function () use ($sale, $amount, $user)
    //     {
    //         if (!$sale->customer_id) {
    //             throw ValidationException::withMessages(['customer' => 'A customer is required for a credit sale.']);
    //         }

    //         if ($amount <= 0) {
    //             throw ValidationException::withMessages(['amount' => 'Customer receivable amount must be greater than zero.']);
    //         }

    //         $existing = CustomerLedgerEntry::query()
    //             ->where('sale_id', $sale->id)
    //             ->first();

    //         if ($existing) {
    //             throw ValidationException::withMessages(['sale' => 'This sale already has a customer receivable entry.']);
    //         }

    //         $customer = Customer::query()
    //             ->lockForUpdate()
    //             ->findOrFail($sale->customer_id);

    //         $this->validateCreditSale($customer, $amount);

    //         return CustomerLedgerEntry::create([
    //             'customer_id'      => $customer->id,
    //             'branch_id'        => $sale->branch_id,
    //             'sale_id'          => $sale->id,
    //             'created_by'       => $user->id,
    //             'type'             => 'sale_credit',
    //             'debit'            => $amount,
    //             'credit'           => 0,
    //             'description'      => 'Credit balance from sale ' . $sale->invoice_number,
    //             'transaction_date' => now(),
    //         ]);
    //     });
    // }

    public function recordSaleCredit(Sale $sale, float $amount, User $user): CustomerLedgerEntry
    {
        return DB::transaction(function () use ($sale, $amount, $user) {

            $sale = Sale::query()
                ->lockForUpdate()
                ->findOrFail($sale->id);

            if ($sale->status !== 'draft') {
                throw ValidationException::withMessages(['sale' => 'Only draft sales can be recorded as credit.']);
            }

            if (! $sale->customer_id) {
                throw ValidationException::withMessages(['customer' => 'A customer is required for a credit sale.']);
            }

            $amount = round((float) $amount, 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Customer receivable amount must be greater than zero.']);
            }

            $alreadyPaid = (float) $sale->payments()
                ->where('status', 'posted')
                ->sum('amount');

            $existingCredit = (float) CustomerLedgerEntry::query()
                ->where('sale_id', $sale->id)
                ->where('type', 'sale_credit')
                ->sum('debit');

            $remainingCredit = round((float) $sale->grand_total - $alreadyPaid - $existingCredit, 2);

            if ($remainingCredit <= 0) {
                throw ValidationException::withMessages(['sale' => 'This sale does not have any remaining credit balance.']);
            }

            if ($amount > $remainingCredit) {
                throw ValidationException::withMessages(['amount' => 'The receivable amount cannot exceed the remaining sale balance.']);
            }

            $existing = CustomerLedgerEntry::query()
                ->where('sale_id', $sale->id)
                ->where('type', 'sale_credit')
                ->first();

            if ($existing) {
                throw ValidationException::withMessages(['sale' => 'This sale already has a customer receivable entry.']);
            }

            $customer = Customer::query()
                ->lockForUpdate()
                ->findOrFail($sale->customer_id);

            $this->validateCreditSale($customer, $amount);

            return CustomerLedgerEntry::create([
                'customer_id' => $customer->id,
                'branch_id' => $sale->branch_id,
                'sale_id' => $sale->id,
                'created_by' => $user->id,
                'type' => 'sale_credit',
                'debit' => $amount,
                'credit' => 0,
                'description' => 'Credit balance from sale '.$sale->invoice_number,
                'transaction_date' => now(),
            ]);
        });
    }
}
