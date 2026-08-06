<?php

namespace App\Domains\Part\Policies;

use App\Domains\Part\Models\Part;
use Illuminate\Foundation\Auth\User;

class PartPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Part $part): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Part $part): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Part $part): bool
    {
        return $user->role === 'Admin';
    }

    public function adjustInventory(User $user): bool
    {
        return $user->role === 'Admin';
    }
}
