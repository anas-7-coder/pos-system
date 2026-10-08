<?php

namespace App\Services;

use App\Models\CashMovement;
use App\Models\CustomerPayment;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\RegisterSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashMovementService
{
    public function recordSalePayment(Payment $payment, User $user): CashMovement
    {
        return DB::transaction(function () use ($payment, $user) {

            if ($payment->payment_method !== 'cash') {
                throw ValidationException::withMessages(['payment' => 'Only cash payments create cash movements.']);
            }

            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($payment->register_session_id);

            if ($session->status !== 'Open') {
                throw ValidationException::withMessages(['payment' => 'The register session is not open.']);
            }

            $existing = CashMovement::query()
                ->where('payment_id', $payment->id)
                ->where('type', 'sale_payment')
                ->first();

            if ($existing) {
                return $existing;
            }

            $movement = CashMovement::create([
                'register_session_id' => $session->id,
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'type' => 'sale_payment',
                // 'amount'              => $payment->received_amount,
                'amount' => $payment->amount,
                'occurred_at' => $payment->paid_at,
                'notes' => 'Cash payment for sale.',
            ]);

            $this->recalculateExpectedCash($session);

            return $movement;
        });
    }

    public function createManualMovement(RegisterSession $session, array $data, User $user): CashMovement
    {
        return DB::transaction(function () use ($session, $data, $user) {
            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($session->id);

            if ($session->status !== 'Open') {
                throw ValidationException::withMessages(['register_session' => 'Cash movements can only be recorded on an open register session.']);
            }

            if (! $user->hasRole('super_admin') && (int) $session->user_id !== (int) $user->id) {
                throw ValidationException::withMessages(['register_session' => 'You can only manage cash in your own register session.']);
            }

            // $amount = round((float) $data['amount'], 2);

            // if ($data['type'] === 'cash_out') {

            //     $expectedCash = $this->calculateExpectedCash($session);

            //     if ($amount > $expectedCash) {
            //         throw ValidationException::withMessages(['amount' => 'Cash out cannot exceed the expected register cash.']);
            //     }
            // }

            $type = $data['type'] ?? null;

            if (! in_array($type, ['cash_in', 'cash_out'], true)) {
                throw ValidationException::withMessages(['type' => 'The selected cash movement type is invalid.']);
            }

            $amount = round((float) ($data['amount'] ?? 0), 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Cash movement amount must be greater than zero.']);
            }

            if ($type === 'cash_out') {
                $expectedCash = $this->calculateExpectedCash($session);

                if ($amount > $expectedCash) {
                    throw ValidationException::withMessages(['amount' => 'Cash out cannot exceed the expected register cash.']);
                }
            }

            $movement = CashMovement::create([
                'register_session_id' => $session->id,
                'user_id' => $user->id,
                'payment_id' => null,
                // 'type'                => $data['type'],
                'type' => $type,
                'amount' => $amount,
                'reference' => $data['reference'] ?? null,
                'notes' => $data['notes'] ?? null,
                'occurred_at' => now(),
            ]);

            $this->recalculateExpectedCash($session);

            return $movement;
        });
    }

    public function calculateExpectedCash(RegisterSession $session): float
    {
        $cashIn = (float) $session->cashMovements()
            ->whereIn('type', ['sale_payment', 'customer_payment', 'cash_in'])
            ->sum('amount');

        $cashOut = (float) $session->cashMovements()
            ->where('type', 'cash_out')
            ->sum('amount');

        return round((float) $session->opening_cash + $cashIn - $cashOut, 2);
    }

    public function recalculateExpectedCash(RegisterSession $session): void
    {
        $expectedCash = $this->calculateExpectedCash($session);
        $session->update(['expected_cash' => $expectedCash]);
    }

    public function recordRefund(Refund $refund, User $user): CashMovement
    {
        return DB::transaction(function () use ($refund, $user) {

            if ($refund->payment_method !== 'cash') {
                throw ValidationException::withMessages(['refund' => 'Only cash refunds create cash movements.']);
            }

            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($refund->register_session_id);

            if ($session->status !== 'Open') {
                throw ValidationException::withMessages(['refund' => 'The register session is not open.']);
            }

            $existing = CashMovement::query()
                ->where('type', 'cash_out')
                ->where('reference', 'Refund #'.$refund->id)
                ->first();

            if ($existing) {
                return $existing;
            }

            $amount = round((float) $refund->amount, 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['refund' => 'Refund amount must be greater than zero.']);
            }

            $expectedCash = $this->calculateExpectedCash($session);

            if ($amount > $expectedCash) {
                throw ValidationException::withMessages(['refund' => 'Cash refund cannot exceed the expected register cash.']);
            }

            $movement = CashMovement::create([
                'register_session_id' => $session->id,
                'user_id' => $user->id,
                'payment_id' => null,
                'type' => 'cash_out',
                // 'amount'              => $refund->amount,
                'amount' => $amount,
                'reference' => 'Refund #'.$refund->id,
                'notes' => 'Cash refund for sale return.',
                'occurred_at' => $refund->refunded_at,
            ]);

            $this->recalculateExpectedCash($session);

            return $movement;
        });
    }

    public function recordCustomerPayment(CustomerPayment $payment, User $user): CashMovement
    {
        return DB::transaction(function () use ($payment, $user) {
            if ($payment->payment_method !== 'cash') {
                throw ValidationException::withMessages(['payment' => 'Only cash customer payments create cash movements.']);
            }

            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($payment->register_session_id);

            if ($session->status !== 'Open') {
                throw ValidationException::withMessages(['payment' => 'The register session is not open.']);
            }

            $movement = CashMovement::create([
                'register_session_id' => $session->id,
                'user_id' => $user->id,
                'payment_id' => null,
                'type' => 'customer_payment',
                // 'amount'              => $payment->received_amount,
                'amount' => $payment->amount,
                'reference' => $payment->payment_number,
                'notes' => 'Customer account payment.',
                'occurred_at' => $payment->paid_at,
            ]);

            $this->recalculateExpectedCash($session);

            return $movement;
        });
    }
}
