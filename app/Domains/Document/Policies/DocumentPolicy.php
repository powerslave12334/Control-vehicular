<?php

namespace App\Domains\Document\Policies;

use App\Domains\Document\Models\Document;
use Illuminate\Foundation\Auth\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Dirección', 'Logística', 'Chofer']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function update(User $user, Document $document): bool
    {
        return in_array($user->role, ['Admin', 'Logística']);
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->role === 'Admin';
    }

    public function restore(User $user, Document $document): bool
    {
        return $user->role === 'Admin';
    }
}
