<?php

namespace App\Domains\Provider\Policies;

use App\Domains\Provider\Models\Provider;
use Illuminate\Foundation\Auth\User;

class ProviderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Provider $provider): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Provider $provider): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Provider $provider): bool
    {
        return $user->role === 'Admin';
    }
}
