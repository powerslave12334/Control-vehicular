<?php

namespace App\Domains\Insurance\Events;

use App\Domains\Insurance\Models\Insurance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class InsuranceExpired
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Insurance $insurance) {}
}
