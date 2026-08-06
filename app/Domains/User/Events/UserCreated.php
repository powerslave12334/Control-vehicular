<?php

namespace App\Domains\User\Events;

use App\Domains\User\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable;

    public function __construct(public readonly User $user) {}
}
