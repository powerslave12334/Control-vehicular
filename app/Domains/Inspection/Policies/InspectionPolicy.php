<?php

namespace App\Domains\Inspection\Policies;

use App\Domains\Inspection\Models\Inspection;
use Illuminate\Foundation\Auth\User;

class InspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function delete(User $user, Inspection $inspection): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Inspection $inspection): bool
    {
        return $user->role === 'Admin';
    }
}
