<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(protected SaleCompletionService $saleCompletionService, protected CashMovementService $cashMovementService) {}

    public function create(Sale $sale, array $data, User $user): Payment
    {
        return DB::transaction(function () use ($sale, $data, $user) {

            $sale = Sale::query()
                ->lockForUpdate()
                ->with(['items.product', 'items.productVariant'])
                ->findOrFail($sale->id);

            if ($sale->status !== 'draft') {
                throw ValidationException::withMessages(['sale' => 'Only draft sales can receive payments.']);
            }

            if (! $sale->register_session_id) {
                throw ValidationException::withMessages(['sale' => 'This sale is not attached to a register session.']);
            }

            $registerSession = $sale->registerSession()
                ->lockForUpdate()
                ->first();

            if (! $registerSession) {
                throw ValidationException::withMessages(['sale' => 'Register session not found.']);
            }

            if ($registerSession->status !== 'Open') {
                throw ValidationException::withMessages(['sale' => 'The register session is not open.']);
            }

            if ((int) $sale->branch_id !== (int) $registerSession->branch_id) {
                throw ValidationException::withMessages(['sale' => 'The sale and register session do not belong to the same branch.']);
            }

            if (! $user->hasRole('super_admin') && (int) $registerSession->user_id !== (int) $user->id) {
                throw ValidationException::withMessages(['sale' => 'You can only receive payments in your own register session.']);
            }

            $alreadyPaid = (float) $sale->payments()
                ->where('status', 'posted')
                ->sum('amount');

            $grandTotal = (float) $sale->grand_total;
            $remaining = round($grandTotal - $alreadyPaid, 2);

            if ($remaining <= 0) {
                throw ValidationException::withMessages(['amount' => 'This sale is already fully paid.']);
            }

            $amount = round((float) $data['amount'], 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Payment amount must be greater than zero.']);
            }

            if ($amount > $remaining) {
                throw ValidationException::withMessages(['amount' => 'Payment amount cannot exceed the remaining balance.']);
            }

            $paymentMethod = $data['payment_method'];

            if (! in_array($paymentMethod, ['cash', 'card', 'bank_transfer', 'mobile_wallet'], true)) {
                throw ValidationException::withMessages(['payment_method' => 'The selected payment method is invalid.']);
            }

            if ($paymentMethod === 'cash') {
                $receivedAmount = round((float) ($data['received_amount'] ?? $amount), 2);

                if ($receivedAmount < $amount) {
                    throw ValidationException::withMessages(['received_amount' => 'Received cash cannot be less than the payment amount.']);
                }

                $changeAmount = round($receivedAmount - $amount, 2);
            } else {
                $receivedAmount = $amount;
                $changeAmount = 0;
            }

            $payment = Payment::create([
                'sale_id' => $sale->id,
                'register_session_id' => $registerSession->id,
                'created_by' => $user->id,
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'received_amount' => $receivedAmount,
                'change_amount' => $changeAmount,
                'reference' => $data['reference'] ?? null,
                'paid_at' => now(),
                'status' => 'posted',
            ]);

            if ($paymentMethod === 'cash') {
                $this->cashMovementService->recordSalePayment($payment, $user);
            }

            $newPaidTotal = round($alreadyPaid + $amount, 2);

            if ($newPaidTotal >= $grandTotal) {
                $this->saleCompletionService->complete($sale);
            }

            return $payment->fresh(['sale', 'registerSession', 'createdBy']);
        });
    }
}
