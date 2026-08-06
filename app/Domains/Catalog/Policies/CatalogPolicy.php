<?php

namespace App\Domains\Catalog\Policies;

use App\Domains\User\Models\User;

class CatalogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección', 'Calidad']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección']);
    }

    public function update(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección']);
    }

    public function delete(User $user): bool
    {
        return $user->role === 'Administrador del sistema';
    }
}
