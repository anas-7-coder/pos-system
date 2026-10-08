<?php

namespace App\Services;

use App\Models\Refund;
use App\Models\RegisterSession;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundService
{
    public function __construct(protected CashMovementService $cashMovementService) {}

    public function create(SaleReturn $saleReturn, array $data, User $user): Refund
    {
        return DB::transaction(function () use ($saleReturn, $data, $user) {

            $saleReturn = SaleReturn::query()
                ->with('sale')
                ->lockForUpdate()
                ->findOrFail($saleReturn->id);

            if ($saleReturn->status !== 'completed') {
                throw ValidationException::withMessages(['return' => 'Only completed returns can be refunded.']);
            }

            if ($saleReturn->refund()->exists()) {
                throw ValidationException::withMessages(['refund' => 'This return has already been refunded.']);
            }

            $session = RegisterSession::query()
                ->lockForUpdate()
                ->findOrFail($data['register_session_id']);

            if ($session->status !== 'Open') {
                throw ValidationException::withMessages(['register_session_id' => 'The register session is not open.']);
            }

            if (! $user->hasRole('super_admin') && (int) $session->user_id !== (int) $user->id) {
                throw ValidationException::withMessages(['register_session_id' => 'You can only process refunds in your own register session.']);
            }

            if ((int) $session->branch_id !== (int) $saleReturn->branch_id) {
                throw ValidationException::withMessages(['register_session_id' => 'The register session does not belong to the return branch.']);
            }

            $paymentMethod = $data['payment_method'] ?? null;

            if (! in_array($paymentMethod, ['cash', 'card', 'bank_transfer', 'mobile_wallet'], true)) {
                throw ValidationException::withMessages(['payment_method' => 'The selected payment method is invalid.']);
            }

            if ($paymentMethod !== 'cash' && empty($data['reference'])) {
                throw ValidationException::withMessages(['reference' => 'A reference is required for non-cash refunds.']);
            }

            $refundAmount = round((float) $saleReturn->grand_total, 2);

            if ($refundAmount <= 0) {
                throw ValidationException::withMessages(['refund' => 'The refund amount must be greater than zero.']);
            }

            $refund = Refund::create([
                'sale_return_id' => $saleReturn->id,
                'sale_id' => $saleReturn->sale_id,
                'register_session_id' => $session->id,
                'created_by' => $user->id,
                // 'payment_method'      => $data['payment_method'],
                'payment_method' => $paymentMethod,
                // 'amount'              => $saleReturn->grand_total,
                'amount' => $refundAmount,
                'reference' => $data['reference'] ?? null,
                'refunded_at' => now(),
                'status' => 'posted',
            ]);

            if ($refund->payment_method === 'cash') {
                $this->cashMovementService->recordRefund(refund: $refund, user: $user);
            }

            $this->updateSaleStatus($saleReturn->sale_id);

            return $refund->fresh(['saleReturn', 'sale', 'registerSession', 'createdBy']);
        });
    }

    protected function updateSaleStatus(int $saleId): void
    {
        // $sale = Sale::query()
        //     ->with('returns.refund')
        //     ->findOrFail($saleId);

        $sale = Sale::query()
            ->with('returns.refund')
            ->lockForUpdate()
            ->findOrFail($saleId);

        $refundedAmount = $sale->returns
            ->flatMap(function ($saleReturn) {
                return $saleReturn->refund ? [$saleReturn->refund] : [];
            })
            ->sum('amount');

        if ($refundedAmount >= (float) $sale->grand_total) {
            $sale->update(['status' => 'refunded']);

            return;
        }

        if ($refundedAmount > 0) {
            $sale->update(['status' => 'partially_refunded']);
        }
    }
}
