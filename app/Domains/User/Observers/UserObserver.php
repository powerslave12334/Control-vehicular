<?php

namespace App\Domains\User\Observers;

use App\Domains\User\Models\User;

class UserObserver
{
    public function creating(User $user): void {}

    public function updating(User $user): void {}
}
