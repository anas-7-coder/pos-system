<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('payments.view');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->hasPermission('payments.view') && $user->canAccessBranch($payment->sale->branch_id);
    }

    public function create(User $user, Sale $sale): bool
    {
        return $user->hasPermission('payments.create') && $sale->status === 'draft' && $user->canAccessBranch($sale->branch_id);
    }
}
