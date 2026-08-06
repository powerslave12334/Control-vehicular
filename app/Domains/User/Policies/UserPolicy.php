<?php

namespace App\Domains\User\Policies;

use App\Domains\User\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección']);
    }

    public function view(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'Administrador del sistema';
    }

    public function update(User $user): bool
    {
        return $user->role === 'Administrador del sistema';
    }

    public function delete(User $user): bool
    {
        return $user->role === 'Administrador del sistema';
    }
}
