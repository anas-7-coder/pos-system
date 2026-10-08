<?php

namespace App\Policies;

use App\Models\RegisterSession;
use App\Models\User;

class RegisterSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('register_sessions.view');
    }

    public function view(User $user, RegisterSession $session): bool
    {
        return $user->hasPermission('register_sessions.view') && $user->canAccessBranch($session->branch_id);
    }

    public function open(User $user): bool
    {
        return $user->hasPermission('register_sessions.open');
    }

    public function close(User $user, RegisterSession $session): bool
    {
        return $user->hasPermission('register_sessions.close') && $user->canAccessBranch($session->branch_id);
    }
}
