<?php

namespace App\Domains\Route\Policies;

use App\Domains\Route\Models\Route;
use Illuminate\Foundation\Auth\User;

class RoutePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer', 'Instalador']);
    }

    public function view(User $user, Route $route): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer', 'Instalador']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Route $route): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Route $route): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Route $route): bool
    {
        return $user->role === 'Admin';
    }

    public function start(User $user, Route $route): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function finish(User $user, Route $route): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function cancel(User $user, Route $route): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }
}
