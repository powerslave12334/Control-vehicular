<?php

namespace App\Domains\Maintenance\Policies;

use App\Domains\Maintenance\Models\Maintenance;
use Illuminate\Foundation\Auth\User;

class MaintenancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
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

    public function start(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function complete(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function cancel(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Maintenance $maintenance): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Maintenance $maintenance): bool
    {
        return $user->role === 'Admin';
    }
}
