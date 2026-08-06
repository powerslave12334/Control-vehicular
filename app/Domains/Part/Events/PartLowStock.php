<?php

namespace App\Domains\Part\Events;

use App\Domains\Part\Models\Part;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class PartLowStock
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Part $part) {}
}
