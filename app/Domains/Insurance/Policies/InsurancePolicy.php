<?php

namespace App\Domains\Insurance\Policies;

use App\Domains\Insurance\Models\Insurance;
use Illuminate\Foundation\Auth\User;

class InsurancePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Insurance $insurance): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Insurance $insurance): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Insurance $insurance): bool
    {
        return $user->role === 'Admin';
    }
}
