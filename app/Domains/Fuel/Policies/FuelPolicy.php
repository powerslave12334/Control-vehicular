<?php

namespace App\Domains\Fuel\Policies;

use App\Domains\Fuel\Models\Refuel;
use Illuminate\Foundation\Auth\User;

class FuelPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function approve(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function reject(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Refuel $refuel): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Refuel $refuel): bool
    {
        return $user->role === 'Admin';
    }
}
