<?php

namespace App\Domains\Gate\Observers;

use App\Domains\Gate\Models\GateLog;

class GateLogObserver
{
    public function creating(GateLog $gateLog): void
    {
        if (!$gateLog->logged_at) {
            $gateLog->logged_at = now();
        }
    }
}
