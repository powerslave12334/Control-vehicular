<?php

namespace App\Domains\WorkOrder\Policies;

use App\Domains\WorkOrder\Models\WorkOrder;
use Illuminate\Foundation\Auth\User;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function approve(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function reject(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function assign(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function close(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === 'Admin';
    }
}
