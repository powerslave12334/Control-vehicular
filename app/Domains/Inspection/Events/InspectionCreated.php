<?php

namespace App\Domains\Inspection\Events;

use App\Domains\Inspection\Models\Inspection;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class InspectionCreated
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Inspection $inspection) {}
}
