<?php

namespace App\Domains\Gate\Actions;

use App\Domains\Gate\Events\GateLogDeleted;
use App\Domains\Gate\Models\GateLog;

class DeleteGateLogAction
{
    public function execute(GateLog $gateLog): void
    {
        $gateLog->delete();
        event(new GateLogDeleted($gateLog));
    }
}
