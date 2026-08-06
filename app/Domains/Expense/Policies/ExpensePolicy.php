<?php

namespace App\Domains\Expense\Policies;

use App\Domains\Expense\Models\Expense;
use Illuminate\Foundation\Auth\User;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística', 'Chofer']);
    }

    public function update(User $user, Expense $expense): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function approve(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function reject(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->role === 'Admin' && $expense->status !== 'aprobado';
    }

    public function restore(User $user, Expense $expense): bool
    {
        return $user->role === 'Admin';
    }
}
