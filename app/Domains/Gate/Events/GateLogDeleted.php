<?php

namespace App\Domains\Gate\Events;

use App\Domains\Gate\Models\GateLog;
use Illuminate\Foundation\Events\Dispatchable;

class GateLogDeleted
{
    use Dispatchable;

    public function __construct(public readonly GateLog $gateLog) {}
}
