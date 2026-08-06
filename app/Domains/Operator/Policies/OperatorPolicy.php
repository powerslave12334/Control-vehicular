<?php

namespace App\Domains\Operator\Policies;

use App\Domains\Operator\Models\Operator;
use Illuminate\Foundation\Auth\User;

class OperatorPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function view(User $user, Operator $operator): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Operator $operator): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Operator $operator): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Operator $operator): bool
    {
        return $user->role === 'Admin';
    }
}
