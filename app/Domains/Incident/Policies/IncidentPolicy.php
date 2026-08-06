<?php

namespace App\Domains\Incident\Policies;

use App\Domains\Incident\Models\Incident;
use Illuminate\Foundation\Auth\User;

class IncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function update(User $user, Incident $incident): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function close(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function escalate(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function resolve(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Incident $incident): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Incident $incident): bool
    {
        return $user->role === 'Admin';
    }
}
