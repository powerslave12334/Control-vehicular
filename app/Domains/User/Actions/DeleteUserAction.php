<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Events\UserDeleted;
use App\Domains\User\Models\User;

class DeleteUserAction
{
    public function execute(User $user): void
    {
        $user->delete();
        event(new UserDeleted($user));
    }
}
