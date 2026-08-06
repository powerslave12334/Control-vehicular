<?php

namespace App\Domains\Vehicle\Policies;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Foundation\Auth\User;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer', 'Instalador']);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer', 'Instalador']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'Admin';
    }

    public function assignOperator(User $user, Vehicle $vehicle): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function releaseOperator(User $user, Vehicle $vehicle): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function viewHistory(User $user, Vehicle $vehicle): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }
}
