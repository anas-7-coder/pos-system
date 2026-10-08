<?php

namespace App\Policies;

use App\Models\CashMovement;
use App\Models\RegisterSession;
use App\Models\User;

class CashMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('cash_management.view');
    }

    public function view(User $user, CashMovement $cashMovement): bool
    {
        return $user->hasPermission('cash_management.view') && $user->canAccessBranch($cashMovement->registerSession->branch_id);
    }

    public function create(User $user, RegisterSession $registerSession): bool
    {
        return $user->hasPermission('cash_management.create') && $registerSession->status === 'Open' && $user->canAccessBranch($registerSession->branch_id);
    }
}
