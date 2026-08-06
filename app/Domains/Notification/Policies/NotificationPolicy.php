<?php

namespace App\Domains\Notification\Policies;

use App\Domains\User\Models\User;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, \App\Domains\Notification\Models\Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    public function delete(User $user, \App\Domains\Notification\Models\Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }
}
