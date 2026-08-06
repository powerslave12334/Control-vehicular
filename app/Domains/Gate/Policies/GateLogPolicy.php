<?php

namespace App\Domains\Gate\Policies;

use App\Domains\User\Models\User;

class GateLogPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Administrador del sistema', 'Dirección', 'Logística', 'Jefe de Distribución']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user): bool
    {
        return $user->role === 'Administrador del sistema';
    }
}
