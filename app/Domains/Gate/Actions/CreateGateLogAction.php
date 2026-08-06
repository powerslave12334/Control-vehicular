<?php

namespace App\Domains\Gate\Actions;

use App\Domains\Gate\DTO\GateLogData;
use App\Domains\Gate\Events\GateLogCreated;
use App\Domains\Gate\Models\GateLog;

class CreateGateLogAction
{
    public function execute(GateLogData $data): GateLog
    {
        $gateLog = GateLog::create([
            'vehicle_id' => $data->vehicle_id,
            'driver_name' => $data->driver_name,
            'type' => $data->type,
            'photo' => $data->photo,
            'notes' => $data->notes,
            'logged_at' => $data->logged_at ?? now(),
        ]);

        event(new GateLogCreated($gateLog));

        return $gateLog;
    }
}
