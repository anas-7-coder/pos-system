<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\User;

class CustomerPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('customer_payments.view');
    }

    public function view(User $user, CustomerPayment $payment): bool
    {
        return $user->hasPermission('customer_payments.view') && $user->canAccessBranch($payment->branch_id);
    }

    public function create(User $user, Customer $customer): bool
    {
        return $user->hasPermission('customer_payments.create') && $customer->status === 'Active';
    }
}
